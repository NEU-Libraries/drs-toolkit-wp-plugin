__author__="Kartik"
import unittest
import os
import inspect
import time
from pyvirtualdisplay import Display
from selenium import webdriver


display = Display(visible=0, size=(800, 800))
display.start()
driver = webdriver.Chrome('/usr/bin/chromedriver')

username = "achinta"
password = "admin"
page_url = "http://54.145.118.52/wordpress/timeline-facet-test"

# Wordpress wp-admin URL
wordpress_url = "http://54.145.118.52/wordpress/wp-login.php"

# DRS Wait tile for index to populate
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
        driver.find_element_by_xpath("//*[@id='menu-pages']/ul/li[3]/a").click()
        driver.find_element_by_id("drs-backbone_modal").click()
        attempt + 1
    except Exception as e:
        if attempt < 5:
            wp_add_page()
        print("Exception produced when creating new page. Error is: ")
        print(e)


class TestTimelineFunctions(unittest.TestCase):

    def setUp(self):
        try:
            # For headless Unix Testing, will not work on Windows as XVFB is not supported
            global driver
            driver = webdriver.Chrome('/usr/bin/chromedriver')
            driver.set_window_size(1280, 720)
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

    # DRS Timeline creator facet present test
    def test1(self):
        try:
            driver.get(page_url)
            self.assertTrue(driver.find_element_by_xpath("//*[@id=\"drs_creator_sim\"]/div/div[1]/b"))
        except Exception as e:
            print("Creator facet not found. Error:")
            print(e)

    # DRS Timeline Creator facet panel value test
    def test2(self):
        try:
            driver.get(page_url)
            creator_value = driver.find_element_by_xpath("//*[@id=\"drs_creator_sim\"]/div/div[2]/a[1]/div[1]").text
            self.assertEquals("Boston Redevelopment Authority Pierce Pearmain.", creator_value)
        except Exception as e:
            print("Creator facet value not found. Error:")
            print(e)


    # DRS Timeline 'Creation year' facet presence test
    def test3(self):
        try:
            driver.get(page_url)
            self.assertTrue(driver.find_element_by_xpath("//*[@id=\"drs_creation_year_sim\"]/div/div[1]/b"))
        except Exception as e:
            print("Creation year facet not found. Error:")
            print(e)

    # DRS Timeline Creation year facet panel value test
    def test4(self):
        try:
            driver.get(page_url)
            creation_year_value = driver.find_element_by_xpath("//*[@id=\"drs_creation_year_sim\"]/div/div[2]/a[3]/div[1]").text
            self.assertEquals("1955", creation_year_value)
        except Exception as e:
            print("Creater facet value not found. Error:")
            print(e)

    # DRS Timeline 'Degree' facet presence test
    def test5(self):
        try:
            driver.get(page_url)
            self.assertTrue(driver.find_element_by_xpath("//*[@id=\"drs_degree_ssim\"]/div/div[1]/b"))
        except Exception as e:
            print("Degree facet not found. Error:")
            print(e)

    # DRS Timeline 'Degree' facet panel value test
    def test6(self):
        try:
            driver.get(page_url)
            degree_value = driver.find_element_by_xpath("//*[@id=\"drs_degree_ssim\"]/div/div[2]/a[1]/div[1]").text
            self.assertEquals("Ed.D.", degree_value)
        except Exception as e:
            print("Degree facet value not found. Error:")
            print(e)

     # DRS Timeline 'Department' facet presence test
    def test7(self):
        try:
            driver.get(page_url)
            self.assertTrue(driver.find_element_by_xpath("//*[@id=\"drs_department_ssim\"]/div/div[1]/b"))
        except Exception as e:
            print("Department facet not found. Error:")
            print(e)

    # DRS Timeline 'Department' facet panel value test
    def test8(self):
        try:
            driver.get(page_url)
            degree_value = driver.find_element_by_xpath("//*[@id=\"drs_department_ssim\"]/div/div[2]/a[1]/div[1]").text
            self.assertEquals("School of Education", degree_value)
        except Exception as e:
            print("Department facet value not found. Error:")
            print(e)

if __name__ == '__main__':
    unittest.main(verbosity=2)
