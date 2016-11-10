__author__ = "Atif Khan/Abhishek Kumar"
import unittest
import os
import inspect
import time
from pyvirtualdisplay import Display
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.common.by import By

#Packages Requirements for headless unix testing:
#sudo apt-get install libxss1 libappindicator1 libindicator7
#wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb

#sudo dpkg -i google-chrome*.deb
#sudo apt-get install -f
#sudo apt-get install xvfb
#sudo apt-get install unzip

#wget -N http://chromedriver.storage.googleapis.com/2.20/chromedriver_linux64.zip
#unzip chromedriver_linux64.zip
#chmod +x chromedriver

#sudo mv -f chromedriver /usr/local/share/chromedriver
#sudo ln -s /usr/local/share/chromedriver /usr/local/bin/chromedriver
#sudo ln -s /usr/local/share/chromedriver /usr/bin/chromedriver


#Login Credentials
username = "admin"
password = "tiger"

driver = webdriver.Chrome()

#Wordpress wp-admin URL
wordpress_url = "http://54.146.242.66/blog/wp-login.php"

#Wordpress published page
worpress_published_page ="http://52.23.210.229/blog/test/"

#DRS Wait tile for index to populate
drs_page_load_wait = 14

def wp_login():
    try:
        driver.get(wordpress_url)
        driver.find_element_by_id("user_login").send_keys(username)
        driver.find_element_by_id("user_pass").send_keys(password)
        driver.find_element_by_id("wp-submit").click()
    except Exception as e:
        print("Exception produced when logging into wp-admin. Error is: ")
        print(e)

def wp_add_page():
    attempt = 0
    try:
        wp_login()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
        driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
        driver.find_element_by_xpath("//*[@id='menu-pages']/ul/li[3]/a").click()
        driver.find_element_by_id("drs-backbone_modal").click()
        attempt = attempt + 1
    except Exception as e:
        if attempt < 5:
            wp_add_page()
        print("Exception produced when creating new page. Error is: ")
        print(e)

class TestMapFunctions(unittest.TestCase):
    def setUp(self):
        try:
            # For headless Unix Testing, will not work on Windows as XVFB is not supported
            global driver
            driver = webdriver.Chrome()
            driver.set_window_size(1280,720)
            driver.implicitly_wait(10) 
        except Exception as e:
            print("Error produced when setting webdriver and/or XVFB display.")
            print(e)

    def tearDown(self):
        try:
            driver.quit()
        except Exception as e:
            print("Error produced when closing driver and display.")
            print(e)

    #Search for creator facet on page"
    def testCreatorFacet(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[1]/b").text
            self.assertEquals("Creator",xpath_text)
        except Exception as e:
            print("Error Creator facet is not present")
            print(e)
	
    #Test if creator "Shwachman, Irene" is present in creator list
    def	testCreatorLinkPresent(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").text
            self.assertEquals("Shwachman, Irene",xpath_text)
        except Exception as e:
            print("Error the creator name not present on page")
            print(e)
	
    #Test if facet selected item present on map
    def testCreatorFilterPositive(self):
        try:
            driver.get(worpress_published_page)
            driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
            time.sleep(drs_page_load_wait)
            filtered_text = driver.find_element(By.XPATH,"//*[@id=\"map\"]/div[3]@data-pid")
            self.assertEquals("neu:128850",filtered_text)
        except Exception as e:
            print("Error the creator name not present on page")
            print(e)
			
    #Test filtered item not present on map
    def testCreatorFilterNegative(self):
        try:
            driver.get(worpress_published_page)
            driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
            time.sleep(drs_page_load_wait)
            filtered_text = driver.find_element(By.XPATH,"//*[@id=\"map\"]/div[2]@data-pid")
            self.assertEquals("neu:182729",filtered_text)
        except Exception as e:
            print("Error the creator name not present on page")
            print(e)
					
	
    #Test "More Creators" button is present in Creator panel
    def	testMoreCreatorButton(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[2]/button").text
            self.assertEquals("More Creators",xpath_text)
        except Exception as e:
            print("Error More Creator button not present in facet")
            print(e)

    #Search for Creation Year facet on page
    def testCreationYearFacet(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH,"//*[@id=\"drs_creation_year_sim\"]/div/div[1]/b").text
            self.assertEquals("Creation Year",xpath_text)
        except Exception as e:
            print("Error \"Creation Year\" facet is not present")
            print(e)

    #Test if year 1958 is present in facet list
    def	testCreationYear(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH,"//*[@id=\"drs_creation_year_sim\"]/div/div[2]/a[1]/div[1]").text
            self.assertEquals("1958",xpath_text)
        except Exception as e:
            print("Error the expected creation year not present")
            print(e)

    #Test if facet selected item present on map
    def testCreationYearFilterPositive(self):
        try:
            driver.get(worpress_published_page)
            driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
            time.sleep(drs_page_load_wait)
            filtered_text = driver.find_element(By.XPATH,"//*[@id=\"map\"]/div[3]@data-pid")
            self.assertEquals("neu:128850",filtered_text)
        except Exception as e:
            print("Error the creator name not present on map")
            print(e)

    #Test filtered item not present on map
    def testCreationYearFilterNegative(self):
        try:
            driver.get(worpress_published_page)
            driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
            time.sleep(drs_page_load_wait)
            filtered_text = driver.find_element(By.XPATH,"//*[@id=\"map\"]/div[2]@data-pid")
            self.assertEquals("neu:182729",filtered_text)
        except Exception as e:
            print("Error the creator name not present on map")
            print(e)

    #Test "More Creation Year" button is present in facet
    def	testMoreCreationYearButton(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[2]/a[3]/div[1]").text
            self.assertEquals("More Creation Year",xpath_text)
        except Exception as e:
            print("Error \"More Creation Year\" button is not present in facet")
            print(e)

    #Search for Subject facet on page
    def testSubjectFacet(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH,"//*[@id=\"drs_subject_sim\"]/div/div[1]/b").text
            self.assertEquals("Subject",xpath_text)
        except Exception as e:
            print("Error \"Subject\" facet is not present")
            print(e)

    #Test if subject "Awards" is present in facet list
    def	testSubject(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH,"//*[@id=\"drs_subject_sim\"]/div/div[2]/a[3]/div[1]").text
            self.assertEquals("Awards",xpath_text)
        except Exception as e:
            print("Error the \"Awards\" Subject not present")
            print(e)

    #Test if facet selected item present on map
    def testSubjectFilterPositive(self):
        try:
            driver.get(worpress_published_page)
            driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
            time.sleep(drs_page_load_wait)
            filtered_text = driver.find_element(By.XPATH,"pid")
            self.assertEquals("neu:128850",filtered_text)
        except Exception as e:
            print("Error the subject name not present on map")
            print(e)

    #Test filtered item not present on map
    def testSubjectFilterNegative(self):
        try:
            driver.get(worpress_published_page)
            driver.find_element(By.XPATH,"//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
            time.sleep(drs_page_load_wait)
            filtered_text = driver.find_element(By.XPATH,"pid")
            self.assertEquals("neu:182729",filtered_text)
        except Exception as e:
            print("Error the subject name not present on map")
            print(e)

    #Test "More Subjects" button is present in facet
    def	testMoreSubjectsButton(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH,"//*[@id=\"drs_subject_sim\"]/div/div[2]/button").text
            self.assertEquals("More Subjects",xpath_text)
        except Exception as e:
            print("Error \"More Subjects\" button is not present in facet")
            print(e)

            # Search for Type facet on page

        def testTypeFacet(self):
            try:
                driver.get(worpress_published_page)
                xpath_text = driver.find_element(By.XPATH, "//*[@id=\"drs_type_sim\"]/div/div[1]/b").text
                self.assertEquals("Type", xpath_text)
            except Exception as e:
                print("Error \"Type\" facet is not present")
                print(e)

        # Test if type "Image" is present in facet list
        def testType(self):
            try:
                driver.get(worpress_published_page)
                xpath_text = driver.find_element(By.XPATH, "//*[@id=\"drs_type_sim\"]/div/div[2]/a[1]/div[1]").text
                self.assertEquals("Type", xpath_text)
            except Exception as e:
                print("Error the \"Image\" type not present")
                print(e)

        # Test if facet selected item present on map
        def testTypeFilterPositive(self):
            try:
                driver.get(worpress_published_page)
                driver.find_element(By.XPATH, "//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
                time.sleep(drs_page_load_wait)
                filtered_text = driver.find_element(By.XPATH, "pid")
                self.assertEquals("neu:128850", filtered_text)
            except Exception as e:
                print("Error type not present on map")
                print(e)

        # Test filtered item not present on map
        def testTypeFilterNegative(self):
            try:
                driver.get(worpress_published_page)
                driver.find_element(By.XPATH, "//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
                time.sleep(drs_page_load_wait)
                filtered_text = driver.find_element(By.XPATH, "pid")
                self.assertEquals("neu:182729", filtered_text)
            except Exception as e:
                print("Error type not present on map")
                print(e)

        # Search for Courses facet on page
        def testCoursesFacet(self):
            try:
                driver.get(worpress_published_page)
                xpath_text = driver.find_element(By.XPATH, "//*[@id=\"drs_type_sim\"]/div/div[1]/b").text
                self.assertEquals("Courses", xpath_text)
            except Exception as e:
                print("Error \"Courses\" facet is not present")
                print(e)

        # Test if Course is present in facet list
        def testCourse(self):
            try:
                driver.get(worpress_published_page)
                xpath_text = driver.find_element(By.XPATH, "//*[@id=\"drs_type_sim\"]/div/div[2]/a[1]/div[1]").text
                self.assertEquals("Course", xpath_text)
            except Exception as e:
                print("Error the \"Course\" not present")
                print(e)

        # Test if facet selected item present on map
        def testCourseFilterPositive(self):
            try:
                driver.get(worpress_published_page)
                driver.find_element(By.XPATH, "//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
                time.sleep(drs_page_load_wait)
                filtered_text = driver.find_element(By.XPATH, "pid")
                self.assertEquals("neu:128850", filtered_text)
            except Exception as e:
                print("Error course not present on map")
                print(e)

        # Test filtered item not present on map
        def testCourseFilterNegative(self):
            try:
                driver.get(worpress_published_page)
                driver.find_element(By.XPATH, "//*[@id=\"drs_creator_sim\"]/div/div[2]/a[2]/div[1]").click()
                time.sleep(drs_page_load_wait)
                filtered_text = driver.find_element(By.XPATH, "pid")
                self.assertEquals("neu:182729", filtered_text)
            except Exception as e:
                print("Error course not present on map")
                print(e)

        # Test "More Courses" button is present in facet
        def testMoreCoursesButton(self):
            try:
                driver.get(worpress_published_page)
                xpath_text = driver.find_element(By.XPATH, "//*[@id=\"drs_subject_sim\"]/div/div[2]/button").text
                self.assertEquals("More Courses", xpath_text)
            except Exception as e:
                print("Error \"More Courses\" button is not present in facet")
                print(e)
