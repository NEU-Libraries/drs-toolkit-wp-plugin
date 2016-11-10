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
wordpress_url = "http://52.23.210.229/blog/wp-login.php"

#Wordpress published page
worpress_published_page ="http://52.23.210.229/blog/test"

#DRS Wait tile for index to populate
drs_page_load_wait = 14

load=20

#Function to login into wordpress
def wp_login():
    print("here")
    try:
        driver.get(wordpress_url)
        driver.find_element_by_id("user_login").send_keys(username)
        driver.find_element_by_id("user_pass").send_keys(password)
        driver.find_element_by_id("wp-submit").click()
    except Exception as e:
        print("Exception produced when logging into wp-admin. Error is: ")
        print(e)

#Fucntion to add page in wordpress
def wp_add_page():
    attempt = 0
    try:
        wp_login()
        driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
        driver.find_element_by_xpath("//*[@id='menu-pages']/ul/li[3]/a").click()
        driver.find_element_by_id("drs-backbone_modal").click()
        attempt = attempt + 1
    except Exception as e:
        if attempt < 5:
            wp_add_page()
        print("Exception produced when creating new page. Error is: ")
        print(e)

class TesttimelineFunctions(unittest.TestCase):
    def setUp(self):
        try:
            # For headless Unix Testing, will not work on Windows as XVFB is not supported
            #global driver
            #driver = webdriver.Chrome()
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

    #Test all individual checkboxes are disabled when select all is checked
    def testDisabled(self):
        try:
            wp_add_page()
            driver.find_element_by_xpath("//*[@id='backbone_modal_dialog']/div[1]/div/section/article/table/tbody/tr[3]/td[2]/a").click()
            time.sleep(drs_page_load_wait)
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if not checkbox.is_selected():
                checkbox.click()
                time.sleep(drs_page_load_wait)
                list_items = driver.find_element_by_xpath("//ol[@id='sortable-timeline-list']")
                list_size = len(list_items)
                for item in list_items:
                    if not driver.find_element_by_tag_name("input").is_enabled():
                        count = count + 1
                if (count == list_size):
                    print("All items disabled")
        except Exception as e:
            print("Error produced items not disabled")
            print(e)

    #Test all individual checkboxes are enabled when select is unchecked
    def testEnabled(self):
        try:
            wp_add_page()
            driver.find_element_by_xpath("//*[@id='backbone_modal_dialog']/div[1]/div/section/article/table/tbody/tr[3]/td[2]/a").click()
            time.sleep(drs_page_load_wait)
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if checkbox.is_selected():
                checkbox.click()
                time.sleep(drs_page_load_wait)
                list_items = driver.find_element_by_xpath("//ol[@id='sortable-timeline-list']")
                list_size = len(list_items)
                for item in list_items:
                    if driver.find_element_by_tag_name("input").is_enabled():
                        count = count + 1
                if (count == list_size):
                    print("All items enabled")
        except Exception as e:
            print("Error produced items not disabled")
            print(e)

    #Test all individual items are selected when select all is checked
    def testSelectAll(self):
        count = 0
        try:
            wp_add_page()
            driver.find_element_by_xpath("//*[@id='backbone_modal_dialog']/div[1]/div/section/article/table/tbody/tr[3]/td[2]/a").click()
            time.sleep(drs_page_load_wait)
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if not checkbox.is_selected():
                checkbox.click()
                time.sleep(drs_page_load_wait)
                list_items = driver.find_element_by_xpath("//ol[@id='sortable-timeline-list']")
                list_size = len(list_items)
                for item in list_items:
                    if(driver.find_element_by_tag_name("input").is_selected()):
                        count = count+1
                if(count==list_size):
                    print("All items checked")
                #driver.find_element_by_xpath("//*[@id='btn-ok']").click()
        except Exception as e:
            print("Error select all checkbox not found")
            print(e)

    #Test all individual itemse are unselected when select all check box is unchecked
    def testUnSelectAll(self):
        count = 0
        try:
            wp_add_page()
            driver.find_element_by_xpath("//*[@id='backbone_modal_dialog']/div[1]/div/section/article/table/tbody/tr[3]/td[2]/a").click()
            time.sleep(drs_page_load_wait)
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if checkbox.is_selected():
                checkbox.click()
                time.sleep(drs_page_load_wait)
                list_items = driver.find_element_by_xpath("//ol[@id='sortable-timeline-list']")
                list_size = len(list_items)
                for item in list_items:
                    if not driver.find_element_by_tag_name("input").is_selected():
                        count = count + 1
                if (count == list_size):
                    print("All items Unchecked")
        except Exception as e:
            print("Error select all checkbox not found")
            print(e)


if __name__ == '__main__':
    unittest.main(verbosity=2)