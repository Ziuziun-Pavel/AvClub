<?php

class Validator {
    private $errors = [];

    public function validateRequired($data, $fields) {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $this->errors[$field] = ucfirst($field) . ' is required.';
            }
        }
    }

    public function validateNumeric($data, $fields) {
        foreach ($fields as $field) {
            if (isset($data[$field]) && !is_numeric($data[$field])) {
                $this->errors[$field] = ucfirst($field) . ' must be a numeric value.';
            }
        }
    }

    public function validateURL($data, $fields) {
        foreach ($fields as $field) {
            if (isset($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_URL)) {
                $this->errors[$field] = ucfirst($field) . ' is not a valid URL.';
            }
        }
    }

    public function validateDate($data, $fields, $format = 'Y-m-d') {
        foreach ($fields as $field) {
            $date = DateTime::createFromFormat($format, $data[$field]);
            if (!$date || $date->format($format) !== $data[$field]) {
                $this->errors[$field] = ucfirst($field) . ' is not a valid date.';
            }
        }
    }

    public function validateDateTime($data, $fields, $format = 'Y-m-d H:i:s') {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $this->errors[$field] = ucfirst($field) . ' is required.';
                continue;
            }

            $date = DateTime::createFromFormat($format, $data[$field]);
            if (!$date || $date->format($format) !== $data[$field]) {
                $this->errors[$field] = ucfirst($field) . ' is not a valid date. Expected format: ' . $format;
            }
        }
    }


    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }
}
