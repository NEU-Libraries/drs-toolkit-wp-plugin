# JENKINS

## SETUP
### Run the following commands

```
wget -q -O - https://jenkins-ci.org/debian/jenkins-ci.org.key | sudo apt-key add -
sudo sh -c 'echo deb http://pkg.jenkins-ci.org/debian binary/ > /etc/apt/sources.list.d/jenkins.list'
sudo apt-get update
sudo apt-get install jenkins
```

### What does this do ?

* Jenkins will be launched as a daemon up on start. See /etc/init.d/jenkins for more details.
* The 'jenkins' user is created to run this service.
* Log file will be placed in /var/log/jenkins/jenkins.log. Check this file if you are troubleshooting Jenkins.
* /etc/default/jenkins will capture configuration parameters for the launch like e.g JENKINS_HOME
* By default, Jenkins listen on port 8080. Access this port with your browser to start configuration.

## Configure
### Secure Jenkins
1. It is recommended to secure Jenkins. Manage Jenkins and then Configure Global Security. 
1. Select the Enable security flag. 
1. The easiest way is to use Jenkins own user database. 
1. Create at least the user "Anonymous" with read access. 
1. Also create entries for the users you want to add in the next step.
1. On the login page, select Create an account to create the users you just gave access.
1. Go to Manage Jenkins, Manage and Assign Roles and then Assign Roles to grant the newly created user additional access rights.

### Install Plugins
1. Go to Manage Jenkins
1. Go into Manage Plugins
1. Install GitHub Authentication plugin
1. Install GitHub plugin

## Setup Automated Jobs
### Build Job
1. Select New Item
1. Give item name
1. Select Freestyle Project
1. Under Source Management, select Git and give repository url `https://github.com/NEU-Libraries/drs-toolkit-wp-plugin.git`
1. Under Build Triggers, select Poll SCM and enter the schedule as `* * * * *`. This will poll the git repository every minute for changes.
1. Under Build, select execute shell and enter the following 
```
cd /var/www/html/wp-content/plugins/drs-tk
git pull
```

### Unit Test Job
1. Select New Item
1. Give item name
1. Select Freestyle Project
1. Under Source Management, select Git and give repository url `https://github.com/NEU-Libraries/drs-toolkit-wp-plugin.git`
1. Under Build, select execute shell and enter the following
```
npm install
grunt jasmine
```
1. Under Post-build Actions, select Publish JUnit test result report
1. Under Test Report XMLs, enter `specs/results/*xml`

### UI Test Job
1. Select New Item
1. Give item name
1. Select Freestyle Project
1. Under Build, select execute shell and enter the following
```
cd /var/www/html/wp-content/plugins/drs-tk/TestSuite
python test-suite-maps.py
python test-suite-timeline.py
```

### Add Dependencies
#### Build Job to Unit Test Job
1. Select Build Job
1. Select Configure
1. Under Post-Build Actions, select Build Other Projects
1. Enter the name of the Unit Test Job
1. Select 'Trigger only if build is stable'
1. Save

#### Unit Test Job to UI Test Job
1. Select Build Job
1. Select Configure
1. Under Post-Build Actions, select Build Other Projects
1. Enter the name of the UI Test Job
1. Select 'Trigger even if the build fails'
1. Save