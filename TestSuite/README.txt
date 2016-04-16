In order to run the Test Suite you will need Python 2.7

-- Downloading Python 2.7 --
This is accessible for GNU/Linux, Mac OSX and Windows here: https://www.python.org/downloads/

-- Installing Python 2.7 --
Installation guides are provided here:

Windows: http://docs.python-guide.org/en/latest/starting/install/win/

Mac OSX: http://www.pyladies.com/blog/Get-Your-Mac-Ready-for-Python-Programming/

Linux: just do sudo apt-get install sudo apt-get install libreadline-gplv2-dev libncursesw5-dev libssl-dev libsqlite3-dev tk-dev libgdbm-dev libc6-dev libbz2-dev python2.7
This will install all the dependencies as well as python itself.

-- Installing pip --
You will need pip in order to install some dependencies the test-suite has. 
These are: pyvirtualdisplay, selenium, inspect and time. 
Here is how you would install pip on different Operating Systems: https://pip.pypa.io/en/stable/installing/

-- Installing dependencies --

Once you have pip installed and open, just run the following commands:

pip install selenium
pip install pyvirtualdisplay
pip install inspect
pip install time

-- Running the Test Suite --
Running the test suite is very easy. 
Ensure you have the chromedriver.exe in the directory where you have the test suite and run these commands:

Windows: Right click on the relevent test suite .py file -> edit with IDLE -> run module

Mac OSX/Linux: python pythonfilehere.py (i.e. python test-suite-maps.py)

-- Notes --
It is also possible to run this test suite in a headless environment. 
For this, we would need XVFB and is only applicable to Unix based systems.
Documentation for doing so is inside of the python file.
However, this is not recommended as you want to see the UI tests. 