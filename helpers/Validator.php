<?php
/**
 * VALIDATION HELPER
 * Rule-based validation
 */

class Validator
{
    private $data;
    private $rules;
    private $errors = [];

    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * Validate data berdasarkan rules
     * 
     * @return bool
     */
    public function validate()
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            
            foreach ($rules as $rule) {
                $this->applyRule($field, $rule);
            }
        }
        
        return empty($this->errors);
    }

    /**
     * Apply rule ke field
     */
    private function applyRule($field, $rule)
    {
        $value = $this->data[$field] ?? null;
        
        // Required
        if ($rule === 'required' && empty($value)) {
            $this->errors[$field][] = "$field is required";
            return;
        }
        
        // Email
        if ($rule === 'email' && !empty($value)) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field][] = "$field must be a valid email";
            }
        }
        
        // Min length
        if (strpos($rule, 'min:') === 0) {
            $min = (int)substr($rule, 4);
            if (!empty($value) && strlen($value) < $min) {
                $this->errors[$field][] = "$field must be at least $min characters";
            }
        }
        
        // Max length
        if (strpos($rule, 'max:') === 0) {
            $max = (int)substr($rule, 4);
            if (!empty($value) && strlen($value) > $max) {
                $this->errors[$field][] = "$field must not exceed $max characters";
            }
        }
        
        // Numeric
        if ($rule === 'numeric' && !empty($value)) {
            if (!is_numeric($value)) {
                $this->errors[$field][] = "$field must be a number";
            }
        }
        
        // Alpha (only letters)
        if ($rule === 'alpha' && !empty($value)) {
            if (!ctype_alpha(str_replace(' ', '', $value))) {
                $this->errors[$field][] = "$field must contain only letters";
            }
        }
    }

    /**
     * Get validation errors
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Static helper untuk quick validation
     */
    public static function make($data, $rules)
    {
        $validator = new self($data, $rules);
        
        if (!$validator->validate()) {
            throw new Exception(json_encode([
                'validation_errors' => $validator->errors()
            ]));
        }
        
        return true;
    }
}