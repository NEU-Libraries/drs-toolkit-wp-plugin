__author__= "Abhishek Kumar"
import unittest
import os
import inspect
import time
from pyvirtualdisplay import Display
from selenium import webdriver
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
username = "drstest"
password = "drstest"

#driver = webdriver.Chrome()

display = Display(visible=0, size=(800, 800))
display.start()

driver = webdriver.Chrome('/usr/bin/chromedriver')

#Wordpress wp-admin URL
wordpress_url = "http://54.145.113.7/blog/wp-login.php"

#Wordpress published page
worpress_published_page ="http://54.145.113.7/blog/maps-facet-test/"

#DRS Wait tile for index to populate
drs_page_load_wait = 14

load=5

#Function to login into wordpress
def wp_login():
    try:
        driver.get(wordpress_url)
        driver.find_element_by_id("user_login").send_keys(username)
        driver.find_element_by_id("user_pass").send_keys(password)
        driver.find_element_by_id("wp-submit").click()
    except Exception as e:
        print("Exception produced when logging into wp-admin. Error is: ")
        print(e)

#Function to add pages into wordpress
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

class TestTimelineFunctions(unittest.TestCase):
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

    #Test all individual checkboxes are checked when select_all is checked
    def testSelectAll(self):
        pre_count = 0
        post_count =0
        try:
            wp_add_page()
            driver.find_element_by_xpath("//*[@id='backbone_modal_dialog']/div[1]/div/section/article/table/tbody/tr[3]/td[2]/a").click()
            time.sleep(drs_page_load_wait)
            items_on_screen = driver.find_elements_by_id("sortable-timeline-list")[0].find_elements_by_tag_name("input")
            for item in items_on_screen:
                if not item.is_selected() :
                    pre_count = pre_count + 1
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if not checkbox.is_selected():
                checkbox.click()
                time.sleep(load)
                items_on_screen = driver.find_elements_by_id("sortable-timeline-list")[0].find_elements_by_tag_name("input")
                for item in items_on_screen:
                    if  item.is_selected():
                        post_count = post_count + 1
            self.assertEqual(pre_count,post_count)
        except Exception as e:
            print("Error produced all items not selected")
            print(e)

    #Test all individual checkboxes are checked when select_all is checked
    def testUnselectAll(self):
        pre_count = 0
        post_count = 0
        try:
            wp_add_page()
            driver.find_element_by_xpath("//*[@id='backbone_modal_dialog']/div[1]/div/section/article/table/tbody/tr[3]/td[2]/a").click()
            time.sleep(drs_page_load_wait)
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if not checkbox.is_selected():
                checkbox.click()
                time.sleep(load)
            items_on_screen = driver.find_elements_by_id("sortable-timeline-list")[0].find_elements_by_tag_name("input")
            for item in items_on_screen:
                if item.is_selected():
                    pre_count = pre_count + 1
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if checkbox.is_selected():
                checkbox.click()
                items_on_screen = driver.find_elements_by_id("sortable-timeline-list")[0].find_elements_by_tag_name("input")
                for item in items_on_screen:
                    if not item.is_selected():
                        post_count = post_count + 1
            self.assertEqual(pre_count, post_count)
        except Exception as e:
            print("Error produced all items not unselected")
            print(e)

    #Test all individual items are disabled when select_all is checked
    def testDisableAll(self):
        pre_count = 0
        post_count = 0
        try:
            wp_add_page()
            driver.find_element_by_xpath("//*[@id='backbone_modal_dialog']/div[1]/div/section/article/table/tbody/tr[3]/td[2]/a").click()
            time.sleep(drs_page_load_wait)
            items_on_screen = driver.find_elements_by_id("sortable-timeline-list")[0].find_elements_by_tag_name("input")
            for item in items_on_screen:
                if item.is_enabled():
                    pre_count = pre_count + 1
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if not checkbox.is_selected():
                checkbox.click()
                time.sleep(load)
                items_on_screen = driver.find_elements_by_id("sortable-timeline-list")[0].find_elements_by_tag_name("input")
                for item in items_on_screen:
                    if not item.is_enabled():
                        post_count = post_count + 1
            self.assertEquals(pre_count, post_count)
        except Exception as e:
            print("Error produced all items not disabled")
            print(e)

    #Test all individual items are enabled when select_all is unchecked
    def testEnableAll(self):
        pre_count = 0
        post_count = 0
        try:
            wp_add_page()
            driver.find_element_by_xpath("//*[@id='backbone_modal_dialog']/div[1]/div/section/article/table/tbody/tr[3]/td[2]/a").click()
            time.sleep(drs_page_load_wait)
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if not checkbox.is_selected():
                checkbox.click()
                time.sleep(load)
            items_on_screen = driver.find_elements_by_id("sortable-timeline-list")[0].find_elements_by_tag_name("input")
            for item in items_on_screen:
                if not item.is_enabled():
                    pre_count = pre_count + 1
            checkbox = driver.find_element_by_xpath("//*[@id='drs-select-all-item']")
            if checkbox.is_selected():
                checkbox.click()
                items_on_screen = driver.find_elements_by_id("sortable-timeline-list")[0].find_elements_by_tag_name("input")
                for item in items_on_screen:
                    if item.is_enabled():
                        post_count = post_count + 1
            self.assertEqual(pre_count, post_count)
        except Exception as e:
            print("Error produced all items not enabled")
            print(e)


if __name__ == '__main__':
    unittest.main(verbosity=2)