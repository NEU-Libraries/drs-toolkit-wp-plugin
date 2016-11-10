import unittest
import os
import inspect
import time
from pyvirtualdisplay import Display
from selenium import webdriver


driver = webdriver.Chrome()
username = "kartik"
password = "Chanana_10"
url = "http://35.162.180.128/blog/48-2/"

# Wordpress wp-admin URL
wordpress_url = "http://35.162.180.128/blog/wp-login.php"

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
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("publish").click()
        attempt = attempt + 1
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
            driver = webdriver.Chrome()
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


    # DRS Timeline index test
    def test1(self):
        wp_add_page()
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        self.assertTrue(driver.find_element_by_xpath(
        "//img[@src='https://repository.library.northeastern.edu/downloads/neu:m039z691p?datastream_id=thumbnail_2']"))

    # DRS Timeline 'creator' facet panel test
    def test3(self):
        # print("Testing to check if the facet panel appears on the timeline page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        try:
            driver.find_element_by_id('drs_creator_sim')
        except NoSuchElementException:
            print ('No facets on page')
            return False
        return True

    # DRS Timeline facet positive response test
    def test4(self):
        # print("Testing to check if the clicking on a facet value filter's items on the page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drs_creator_sim']/div/div[1]/a[0]").click()
        self.assertTrue(driver.find_element_by_xpath(
                "//img[@src='https://repository.library.northeastern.edu/downloads/neu:125676?datastream_id=thumbnail_2']"))

    # DRS Timeline facet more options test
    def test5(self):
        # print("Testing to check if the clicking on more button adds choices to the facet on the page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drs_creator_sim']/div/div[1]/button").click()
        try:
            driver.find_element_by_xpath("//*[@class='modal_body']/a[5]")
        except NoSuchElementException:
            print ('No more facet options found')
            return False
        return True



    # DRS Timeline 'creation year' facet panel test
    def test7(self):
        # print("Testing to check if the facet panel appear on the timeline page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        try:
            driver.find_element_by_id('drs_creation_year_sim')
        except NoSuchElementException:
            print ('No creation_year facet on page')
            return False
        return True

    # DRS Timeline facet positive response test
    def test8(self):
        # print("Testing to check if the clicking on a facet value filter's items on the page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drs_creation_year_sim']/div/div[1]/a[0]").click()
        self.assertTrue(driver.find_element_by_xpath(
                "//img[@src='https://repository.library.northeastern.edu/downloads/neu:132176?datastream_id=thumbnail_2']"))

    # DRS Timeline facet more options test
    def test9(self):
        # print("Testing to check if the clicking on more button adds choices to creation_year facet on the page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drs_creation_year_sim']/div/div[1]/button").click()
        try:
            driver.find_element_by_xpath("//*[@class='modal_body']/a[5]")
        except NoSuchElementException:
            print ('No more facet options found')
            return False
        return True

    # DRS Timeline 'Subject' facet panel test
    def test11(self):
        # print("Testing to check if the subject facet panels appear on the timeline page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        try:
            driver.find_element_by_id('drs_subject_sim')
        except NoSuchElementException:
            print ('No subject facet on page')
            return False
        return True

    # DRS Timeline facet positive response test
    def test12(self):
        # print("Testing to check if the clicking on a facet value filter's items on the page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drs_subject_sim']/div/div[1]/a[0]").click()
        time.sleep(drs_page_load_wait)
        self.assertTrue(driver.find_element_by_xpath(
                    "//img[@src='https://repository.library.northeastern.edu/downloads/neu:182870?datastream_id=thumbnail_2']"))

    # DRS Timeline facet more options test
    def test13(self):
        # print("Testing to check if the clicking on more button adds choices to a facet on the page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drs_creater_sim']/div/div[1]/button").click()
        try:
            driver.find_element_by_xpath("//*[@class='modal_body']/a[5]")
        except NoSuchElementException:
            print ('No more facet options found')
            return False
        return True

    # DRS Timeline 'type' facet panel test
    def test14(self):
        # print("Testing to check if the type facet panel appears on the timeline page")
        wp_add_page()
        search_keyword = "boston"
        driver.find_element_by_class("fa fa-search").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        try:
            driver.find_element_by_id('drs_type_sim')
        except NoSuchElementException:
            print ('No type facet on page')
            return False
        return True

    # DRS Timeline facet positive response test
    def test15(self):
            # print("Testing to check if the clicking on a facet value filter's items on the page")
            wp_add_page()
            search_keyword = "boston"
            driver.find_element_by_class("fa fa-search").click()
            time.sleep(drs_page_load_wait)
            time.sleep(drs_page_load_wait)
            driver.find_element_by_xpath("//*[@id='drs_type_sim']/div/div[1]/a[0]").click()
            self.assertTrue(driver.find_element_by_xpath(
                    "//img[@src='https://repository.library.northeastern.edu/downloads/neu:124316?datastream_id=thumbnail_2']"))


if __name__ == '__main__':
    unittest.main(verbosity=2)
