<?php namespace XoopsModules\Pedigree;

/**
 *  Copyright 2005 Zervaas Enterprises (www.zervaas.com.au)
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

/**
 * ZervWizard
 *
 * A class to manage multi-step forms or wizards. This involves managing
 * the various steps, storing its values and switching between each
 * step
 *
 * @author  Quentin Zervaas
 */

class ZervWizard
{
    // whether or not all steps of the form are complete
    public $_complete = false;

    // internal array to store the various steps
    public $_steps = [];

    // the current step
    public $_currentStep = null;

    // the prefix of the container key where form values are stored
    public $_containerPrefix = '__wiz_';

    // an array of any errors that have occurred
    public $_errors = [];

    // key in container where step status is stored
    public $_step_status_key = '__step_complete';

    // key in container where expected action is stored
    public $_step_expected_key = '__expected_action';

    // options to use for the wizard
    public $options = ['redirectAfterPost' => true];

    // action that resets the container
    public $resetAction = '__reset';

    /**
     * ZervWizard
     *
     * Constructor. Primarily sets up the container
     *
     * @param array  &$container Reference to container array
     * @param string $name       A unique name for the wizard for container storage
     */
    public function __construct($container, $name)
    {
        if (!is_array($container)) {
            $this->addError('container', 'Container not valid');

            return;
        }

        $containerKey = $this->_containerPrefix . $name;
        if (!array_key_exists($containerKey, $container)) {
            $container[$containerKey] = [];
        }

        $this->container =& $container[$containerKey];

        if (!array_key_exists('_errors', $this->container)) {
            $this->container['_errors'] = [];
        }
        $this->_errors =& $this->container['_errors'];
    }

    /**
     * process
     *
     * Processes the form for the specified step. If the processed step
     * is complete, then the wizard is set to use the next step. If this
     * is the initial call to process, then the wizard is set to use the
     * first step. Once the next step is determined, the prepare method
     * is called for the step. This has the method name prepare_[step name]()
     *
     * @todo    Need a way to jump between steps, e.g. from step 2 to 4 and validating all data
     *
     * @param string|null $action  The step being processed. This should correspond
     *                        to a step created in addStep()
     * @param array  &$form   The unmodified form values to process
     * @param bool   $process True if the step is being processed, false if being prepared
     */
    public function process($action, $form, $process = true)
    {
        if ($action == $this->resetAction) {
            $this->clearContainer();
            $this->setCurrentStep($this->getFirstIncompleteStep());
        } elseif (isset($form['reset'])) {
            $this->clearContainer();
            $this->setCurrentStep($this->getFirstIncompleteStep());
        } elseif (isset($form['previous']) && !$this->isFirstStep()) {
            // clear out errors
            $this->_errors = [];

            $this->setCurrentStep($this->getPreviousStep($action));
            $this->doRedirect();
        } elseif (isset($form['addvalue']) && !$this->isFirstStep()) {
            // clear out errors
            $this->_errors = [];

            // processing callback must exist and validate to proceed
            $callback = 'process' . $action;
            $complete = method_exists($this, $callback) && $this->$callback($form);

            $this->container[$this->_step_status_key][$action] = $complete;
            $this->setCurrentStep($action);
        } else {
            $proceed = false;

            // check if the step to be processed is valid
            if ('' === $action) {
                $action = $this->getExpectedStep();
            }

            if ($this->stepCanBeProcessed($action)) {
                if ($this->getStepNumber($action) <= $this->getStepNumber($this->getExpectedStep())) {
                    $proceed = true;
                } else {
                    $proceed = false;
                }
            }

            if ($proceed) {
                if ($process) {
                    // clear out errors
                    $this->_errors = [];

                    // processing callback must exist and validate to proceed
                    $callback = 'process' . $action;
                    $complete = method_exists($this, $callback) && $this->$callback($form);

                    $this->container[$this->_step_status_key][$action] = $complete;

                    if ($complete) {
                        $this->setCurrentStep($this->getFollowingStep($action));
                    } // all ok, go to next step
                    else {
                        $this->setCurrentStep($action);
                    } // error occurred, redo step

                    // final processing once complete
                    if ($this->isComplete()) {
                        $this->completeCallback();
                    }

                    $this->doRedirect();
                } else {
                    $this->setCurrentStep($action);
                }
            } else {
                // when initally starting the wizard

                $this->setCurrentStep($this->getFirstIncompleteStep());
            }
        }

        // setup any required data for this step
        $callback = 'prepare' . $this->getStepName();
        if (method_exists($this, $callback)) {
            $this->$callback();
        }
    }

    /**
     * completeCallback
     *
     * Function to run once the final step has been processed and is valid.
     * This should be overwritten in child classes
     */
    public function completeCallback()
    {
    }

    public function doRedirect()
    {
        if ($this->coalesce($this->options['redirectAfterPost'], false)) {
            $redir = $_SERVER['REQUEST_URI'];
            $redir = preg_replace('/\?' . preg_quote($_SERVER['QUERY_STRING'], '/') . '$/', '', $redir);
            header('Location: ' . $redir);
            exit;
        }
    }

    /**
     * isComplete
     *
     * Check if the form is complete. This can only be properly determined
     * after process() has been called.
     *
     * @return bool True if the form is complete and valid, false if not
     */
    public function isComplete()
    {
        return $this->_complete;
    }

    /**
     * setCurrentStep
     *
     * Sets the current step in the form. This should generally only be
     * called internally but you may have reason to change the current
     * step.
     *
     * @param string|null $step The step to set as current
     */
    public function setCurrentStep($step)
    {
        if (null === $step || !$this->stepExists($step)) {
            $this->_complete                            = true;
            $this->container[$this->_step_expected_key] = null;
        } else {
            $this->_currentStep                         = $step;
            $this->container[$this->_step_expected_key] = $step;
        }
    }

    /**
     * @return mixed|null
     */
    public function getExpectedStep()
    {
        $step = $this->coalesce($this->container[$this->_step_expected_key], null);
        if ($this->stepExists($step)) {
            return $step;
        }

        return null;
    }

    /**
     * stepExists
     *
     * Check if the given step exists
     *
     * @param string $stepname The name of the step to check for
     *
     * @return bool True if the step exists, false if not
     */
    public function stepExists($stepname)
    {
        return array_key_exists($stepname, $this->_steps);
    }

    /**
     * getStepName
     *
     * Get the name of the current step
     *
     * @return string The name of the current step
     */
    public function getStepName()
    {
        return $this->_currentStep;
    }

    /**
     * getStepNumber
     *
     * Gets the step number (from 1 to N where N is the number of steps
     * in the wizard) of the current step
     *
     * @param string $step Optional. The step to get the number for. If null then uses current step
     *
     * @return int The number of the step. 0 if something went wrong
     */
    public function getStepNumber($step = null)
    {
        $steps    = array_keys($this->_steps);
        $numSteps = count($steps);

        if ('' === $step) {
            $step = $this->getStepName();
        }

        $ret = 0;
        for ($n = 1; $n <= $numSteps && 0 == $ret; ++$n) {
            if ($step == $steps[$n - 1]) {
                $ret = $n;
            }
        }

        return $ret;
    }

    /**
     * @param $step
     *
     * @return bool
     */
    public function stepCanBeProcessed($step)
    {
        $steps    = array_keys($this->_steps);
        $numSteps = count($steps);

        foreach ($steps as $iValue) {
            $_step = $iValue;
            if ($_step == $step) {
                break;
            }

            if (!$this->container[$this->_step_status_key][$_step]) {
                return false;
            }
        }

        return true;
    }

    /**
     * getStepProperty
     *
     * Retrieve a property for a given step. At this stage, the only
     * property steps have is a title property.
     *
     * @param string $key     The key to get a property for
     * @param mixed  $default The value to return if the key isn't found
     *
     * @return mixed The property value or the default value
     */
    public function getStepProperty($key, $default = null)
    {
        $step = $this->getStepName();
        if (isset($this->_steps[$step][$key])) {
            return $this->_steps[$step][$key];
        }

        return $default;
    }

    /**
     * getFirstStep
     *
     * Get the step name of the first step
     *
     * @return string The name of the first step, or null if no steps
     */
    public function getFirstStep()
    {
        $steps = array_keys($this->_steps);

        return count($steps) > 0 ? $steps[0] : null;
    }

    /**
     * @return null
     */
    public function getFirstIncompleteStep()
    {
        $steps    = array_keys($this->_steps);
        $numSteps = count($steps);

        foreach ($steps as $iValue) {
            $_step = $iValue;

            if (!array_key_exists($this->_step_status_key, $this->container)
                || !$this->container[$this->_step_status_key][$_step]) {
                return $_step;
            }
        }

        return null;
    }

    /**
     * getPreviousStep
     *
     * Gets the step name of the previous step. If the current
     * step is the first step, then null is returned
     *
     * @param $step
     *
     * @return string The name of the previous step, or null
     */
    public function getPreviousStep($step)
    {
        $ret   = null;
        $steps = array_keys($this->_steps);

        $done = false;
        foreach ($steps as $s) {
            if ($s == $step) {
                $done = true;
                break;
            }
            $ret = $s;
        }

        return $ret;
    }

    /**
     * getFollowingStep
     *
     * Get the step name of the next step. If the current
     * step is the last step, returns null
     *
     * @param $step
     *
     * @return string The name of the next step, or null
     */
    public function getFollowingStep($step)
    {
        $ret   = null;
        $steps = array_keys($this->_steps);

        $ready = false;
        foreach ($steps as $s) {
            if ($s == $step) {
                $ready = true;
            } else {
                if ($ready) {
                    $ret = $s;
                    break;
                }
            }
        }

        return $ret;
    }

    /**
     * addStep
     *
     * Adds a step to the wizard
     *
     * @param string $stepname The name of the step
     * @param string $title    The title of the current step
     */
    public function addStep($stepname, $title)
    {
        if (array_key_exists($stepname, $this->_steps)) {
            $this->addError('step', 'Step with name ' . $stepname . ' already exists');

            return;
        }

        $this->_steps[$stepname] = ['title' => $title];

        if (!array_key_exists($this->_step_status_key, $this->container)) {
            $this->container[$this->_step_status_key] = [];
        }

        if (!array_key_exists($stepname, $this->container[$this->_step_status_key])) {
            $this->container[$this->_step_status_key][$stepname] = false;
        }
    }

    /**
     * isFirstStep
     *
     * Check if the current step is the first step
     *
     * @return bool True if the current step is the first step
     */
    public function isFirstStep()
    {
        $steps = array_keys($this->_steps);

        return count($steps) > 0 && $steps[0] == $this->getStepName();
    }

    /**
     * isLastStep
     *
     * Check if the current step is the last step
     *
     * @return bool True if the current step is the last step
     */
    public function isLastStep()
    {
        $steps = array_keys($this->_steps);

        return count($steps) > 0 && array_pop($steps) == $this->getStepName();
    }

    /**
     * setValue
     *
     * Sets a value in the container
     *
     * @param string $key The key for the value to set
     * @param mixed  $val The value
     */
    public function setValue($key, $val)
    {
        $this->container[$key] = $val;
    }

    /**
     * getValue
     *
     * Gets a value from the container
     *
     * @param string $key     The key for the value to get
     * @param mixed  $default The value to return if the key doesn't exist
     *
     * @return mixed Either the key's value or the default value
     */
    public function getValue($key, $default = null)
    {
        return $this->coalesce($this->container[$key], $default);
    }

    /**
     * clearContainer
     *
     * Removes all data from the container. This is primarily used
     * to reset the wizard data completely
     */
    public function clearContainer()
    {
        foreach ($this->container as $k => $v) {
            unset($this->container[$k]);
        }
    }

    /**
     * coalesce
     *
     * Initializes a variable, by returning either the variable
     * or a default value
     *
     * @param mixed &$var    The variable to fetch
     * @param mixed $default The value to return if variable doesn't exist or is null
     *
     * @return mixed The variable value or the default value
     */
    public function coalesce(&$var, $default = null)
    {
        return (isset($var) && null !== $var) ? $var : $default;
    }

    /**
     * addError
     *
     * Add an error
     *
     * @param string $key An identifier for the error (e.g. the field name)
     * @param string $val An error message
     */
    public function addError($key, $val)
    {
        $this->_errors[$key] = $val;
    }

    /**
     * isError
     *
     * Check if an error has occurred
     *
     * @param string $key The field to check for error. If none specified checks for any error
     *
     * @return bool True if an error has occurred, false if not
     */
    public function isError($key = null)
    {
        if (null !== $key) {
            return array_key_exists($key, $this->_errors);
        }

        return count($this->_errors) > 0;
    }

    /**
     * @param $key
     *
     * @return null
     */
    public function getError($key)
    {
        return array_key_exists($key, $this->_errors) ? $this->_errors[$key] : null;
    }
}
