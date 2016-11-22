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
username = "achinta"
password = "admin"

display = Display(visible=0, size=(800, 800))
display.start()

driver = webdriver.Chrome('/usr/bin/chromedriver')

#Wordpress wp-admin URL
wordpress_url = "http://54.145.118.52/wordpress/wp-login.php"

#Wordpress published page
worpress_published_page ="http://54.145.118.52/wordpress/maps-facet-test/"

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

class TestMapFacetFunctions(unittest.TestCase):
    def setUp(self):
        try:
            # For headless Unix Testing, will not work on Windows as XVFB is not supported
            global driver
            driver = webdriver.Chrome('/usr/bin/chromedriver')
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

    # Test if create facet is present on page"
    def testCreatorFacet(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH, "//*[@id='drs_creator_sim']/div/div[1]/b").text
            self.assertEquals("Creator", xpath_text)
        except Exception as e:
            print("Error Creator facet is not present")
            print(e)

    # Test if creator "Shwachman, Irene" is present in creator list
    def testCreatorLinkPresent(self):
            creator_name = []
            try:
                driver.get(worpress_published_page)
                creator_list = driver.find_elements_by_xpath("//*[@id='drs_creator_sim']/div/div[2]/a")
                for creator in creator_list:
                    creator_name.append(creator.find_elements_by_tag_name("div")[0].text)
                self.assertIn("Shwachman, Irene", creator_name)
            except Exception as e:
                print("Error the creator name not present on page")
                print(e)

    # Test if unkown creator present in creator list
    def testCreatorLinkNotPresent(self):
            creator_name = []
            try:
                driver.get(worpress_published_page)
                creator_list = driver.find_elements_by_xpath("//*[@id='drs_creator_sim']/div/div[2]/a")
                for creator in creator_list:
                    creator_name.append(creator.find_elements_by_tag_name("div")[0].text)
                self.assertNotIn("ABCDXYZ", creator_name)
            except Exception as e:
                print("Error the creator name not present on page")
                print(e)

    # Search for Creation Year facet on page
    def testCreationYearFacet(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH, "//*[@id='drs_creation_year_sim']/div/div[1]/b").text
            self.assertEquals("Creation Year", xpath_text)
        except Exception as e:
            print("Error Creation Year facet is not present")
            print(e)

    # Test if year 1958 is present in facet list
    def testCreationYearPresent(self):
        creator_name = []
        try:
            driver.get(worpress_published_page)
            creator_list = driver.find_elements_by_xpath("//*[@id='drs_creation_year_sim']/div/div[2]/a")
            for creator in creator_list:
                creator_name.append(creator.find_elements_by_tag_name("div")[0].text)
            self.assertIn("1958", creator_name)
        except Exception as e:
            print("Error the Creation Year not present on page")
            print(e)

    #Test if unknown creation year present in the list.
    def testCreationYearNotPresent(self):
        creator_name = []
        try:
            driver.get(worpress_published_page)
            creator_list = driver.find_elements_by_xpath("//*[@id='drs_creation_year_sim']/div/div[2]/a")
            for creator in creator_list:
                creator_name.append(creator.find_elements_by_tag_name("div")[0].text)
            self.assertNotIn("20000", creator_name)
        except Exception as e:
            print("Error the Creation Year not present on page")
            print(e)

    # Search for Subject facet on page
    def testSubjectFacet(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH, "//*[@id='subject_sim']/div/div[1]/b").text
            self.assertEquals("Subject", xpath_text)
        except Exception as e:
            print("Error \"Subject\" facet is not present")
            print(e)


    # Test if subject "Youth" is present in facet list
    def testSubjectPresent(self):
        subject_name = []
        driver.get(worpress_published_page)
        try:
            subject_list = driver.find_elements_by_xpath("//*[@id='subject_sim']/div/div[2]/a")
            for subject in subject_list:
                subject_name.append(subject.find_elements_by_tag_name("div")[0].text)
            self.assertIn("Youth", subject_name)
        except Exception as e:
            print("Error Subject not present")
            print(e)

    # Test if unknown subject is not present in facet list
    def testSubjectNotPresent(self):
        subject_name = []
        driver.get(worpress_published_page)
        try:
            subject_list = driver.find_elements_by_xpath("//*[@id='subject_sim']/div/div[2]/a")
            for subject in subject_list:
                subject_name.append(subject.find_elements_by_tag_name("div")[0].text)
            self.assertNotIn("ABCDXYZ", subject_name)
        except Exception as e:
            print("Error Subject not present")
            print(e)

    # Search for Degree facet on page
    def testDegreeFacet(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH, "//*[@id='drs_degree_ssim']/div/div[1]/b").text
            self.assertEquals("Degree", xpath_text)
        except Exception as e:
            print("Error \"Degree\" facet is not present")
            print(e)

    # Test if degree "Ed.D." is present in facet list
    def testDegreePresent(self):
        degree_name = []
        driver.get(worpress_published_page)
        try:
            degree_list = driver.find_elements_by_xpath("//*[@id='drs_degree_ssim']/div/div[2]/a")
            for subject in degree_list:
                degree_name.append(subject.find_elements_by_tag_name("div")[0].text)
            self.assertIn("Ed.D.", degree_name)
        except Exception as e:
            print("Error Degree not present")
            print(e)

    # Test if  unknown degree is present in facet list
    def testDegreeNotPresent(self):
        degree_name = []
        driver.get(worpress_published_page)
        try:
            degree_list = driver.find_elements_by_xpath("//*[@id='drs_degree_ssim']/div/div[2]/a")
            for subject in degree_list:
                degree_name.append(subject.find_elements_by_tag_name("div")[0].text)
            self.assertNotIn("ABCDXYZ", degree_name)
        except Exception as e:
            print("Error Degree not present")
            print(e)

    # Search for Department facet on page
    def testDepartmentFacet(self):
        try:
            driver.get(worpress_published_page)
            xpath_text = driver.find_element(By.XPATH, "//*[@id='drs_department_ssim']/div/div[1]/b").text
            self.assertEquals("Department", xpath_text)
        except Exception as e:
            print("Error \"Department\" facet is not present")
            print(e)

    # Test if department "School of Education" is present in facet list
    def testDepartmentPresent(self):
        department_name = []
        driver.get(worpress_published_page)
        try:
            department_list = driver.find_elements_by_xpath("//*[@id='drs_department_ssim']/div/div[2]")
            for subject in department_list:
                department_name.append(subject.find_elements_by_tag_name("div")[0].text)
            self.assertIn("School of Education", department_name)
        except Exception as e:
            print("Error Department not present")
            print(e)

    #Test if unknown subject is  present in facet list
    def testDepartmentNotPresent(self):
        department_name = []
        driver.get(worpress_published_page)
        try:
            degree_list = driver.find_elements_by_xpath("//*[@id='drs_department_ssim']/div/div[2]")
            for subject in degree_list:
                department_name.append(subject.find_elements_by_tag_name("div")[0].text)
            self.assertNotIn("ABCDXYZ", department_name)
        except Exception as e:
            print("Error Department not present")
            print(e)

if __name__ == '__main__':
    unittest.main(verbosity=2)

