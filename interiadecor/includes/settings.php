<?php
class Settings {
    private $db;
    
    public function __construct() {
        global $db;
        $this->db = $db;
    }
    
    // Get all settings
    public function getAllSettings() {
        $this->db->query('SELECT * FROM settings');
        $settings = $this->db->resultSet();
        
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->setting_key] = $setting->setting_value;
        }
        
        return $result;
    }
    
    // Get a single setting
    public function getSetting($key) {
        $this->db->query('SELECT setting_value FROM settings WHERE setting_key = :key');
        $this->db->bind(':key', $key);
        $result = $this->db->single();
        return $result ? $result->setting_value : null;
    }
    
    // Update a setting
    public function updateSetting($key, $value) {
        $this->db->query('SELECT id FROM settings WHERE setting_key = :key');
        $this->db->bind(':key', $key);
        $exists = $this->db->single();
        
        if ($exists) {
            $this->db->query('UPDATE settings SET setting_value = :value WHERE setting_key = :key');
        } else {
            $this->db->query('INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)');
        }
        
        $this->db->bind(':key', $key);
        $this->db->bind(':value', $value);
        
        return $this->db->execute();
    }
    
    // Update multiple settings
    public function updateSettings($data) {
        foreach ($data as $key => $value) {
            $this->updateSetting($key, $value);
        }
        return true;
    }
    
    // Get theme settings
    public function getThemeSettings() {
        $themeSettings = [
            'theme_primary',
            'theme_secondary',
            'theme_accent',
            'theme_background',
            'theme_text',
            'theme_light',
            'theme_dark'
        ];
        
        $result = [];
        foreach ($themeSettings as $setting) {
            $result[$setting] = $this->getSetting($setting);
        }
        
        return $result;
    }
    
    // Generate CSS variables for theme
    public function generateThemeCSS() {
        $theme = $this->getThemeSettings();
        
        $css = ":root {\n";
        foreach ($theme as $key => $value) {
            if ($value) {
                $css .= "  --" . str_replace('_', '-', $key) . ": " . $value . ";\n";
            }
        }
        $css .= "}\n";
        
        return $css;
    }
    
    // Save theme CSS to file
    public function saveThemeCSS() {
        $css = $this->generateThemeCSS();
        $filePath = 'public/assets/css/theme-light.css';
        file_put_contents($filePath, $css);
        return true;
    }
}

$settings = new Settings();