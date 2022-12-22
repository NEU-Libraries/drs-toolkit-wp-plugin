<?php
namespace Ceres\Util;

use Ceres\Exception;

require_once('/var/www/html/wordpress2/wp-content/plugins/drs-tk/util/ceres_settings.php');


class DataUtilities {

    protected static $allOptions = [];
    protected static $optionValues = [];
    protected static $viewTemplates = [];

    // $scope is ceres, {project_name}, {view_package_name}
    static function valueForOption($optionName, $scope = null) {
        self::setProperties();
        if(isset(self::$optionValues[$optionName])) {
            $optionValues = self::$optionValues[$optionName];
            if (!empty($optionValues['currentValue'])) {
                return $optionValues['currentValue'];
            }

            if (!empty($scope)) {
                return $optionValues['defaults'][$scope];
            }

            // no precision to the scope value wanted, so
            // bubble up the hierarchy of default values

            $viewPackageName = ''; //@todo: dig this up
            $projectName = ''; // @todo dig this up

            // @todo this could be handled w/out the multiple ifs somehow
            // to maintain the hierarchy in a more versatile way
            if (!empty($optionValues['defaults'][$viewPackageName])
                && !empty($viewPackageName)
            ) {
                return $optionValues['defaults'][$viewPackageName];
            }

            if (!empty($optionValues['defaults'][$projectName])
                && !empty($projectName)
            ) {
                return $optionValues['defaults'][$projectName];
            }    

            if (!empty($optionValues['defaults']['ceres'])) {
                return $optionValues['defaults']['ceres'];
            }

            // return false or throw a Ceres\Exception
        }
    }

    static function defaultsForOption($optionName) {
        self::setProperties();
        return self::$optionValues[$optionName]['defaults'];
    }

    static function userHasAccess($user, $optionName) {
        self::setProperties();
        $userRole = ''; //@todo: dig this up, or have wp something something something
        if (in_array($userRole, self::$allOptions[$optionName]['access'])) {
            return true;
        }

        return false; // or throw something?

    }

    static function setProperties() {
//for real WP integration
        // self::$viewTemplates = get_option('ceres_view_templates');
        // self::$allOptions = get_option('ceres_all_options');
        // self::$optionValues = get_option('ceres_option_values');

//for dev/testing
        self::$viewTemplates = getViewPackages();
        self::$allOptions = getAllOptions();
        self::$optionValues = getOptionsValues();
    }

}
