__author__= "Kartik Chanana"
import unittest
import os
import inspect
import time
from pyvirtualdisplay import Display
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys

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

#driver = webdriver.Chrome('/usr/bin/chromedriver')

#Wordpress wp-admin URL
wordpress_url = "http://54.146.130.149/wordpress/wp-login.php"

#Wordpress published page
worpress_published_page ="http://54.146.130.149/wordpress/wp-admin/post-new.php?post_type=page#"


#DRS Wait tile for index to populate
drs_page_load_wait = 14

load=20

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

class TestMapSearchFunctions(unittest.TestCase):
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

    def wp_login(self):
        try:
            driver.get(wordpress_url)
            driver.find_element_by_id("user_login").send_keys(username)
            driver.find_element_by_id("user_pass").send_keys(password)
            driver.find_element_by_id("wp-submit").click()
        except Exception as e:
            print("Exception produced when logging into wp-admin. Error is: ")
            print(e)

    #Test search positive on map items
    def testSearchBarPresent(self):
        try:
            driver.get(worpress_published_page)
            self.assertTrue(driver.find_element_by_xpath("//*[@id='search-and-facet']/form/input"))
        except Exception as e:
            print("Couldn't find the search bar.")
            print(e)


    def testColorPaletteExist(self):
        try:
            wp_login()
            time.sleep(drs_page_load_wait)
            driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
            driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
            driver.find_element_by_xpath("//*[@id='menu-pages']/ul/li[3]/a").click()
            driver.find_element_by_id("drs-backbone_modal").click()
            driver.find_element_by_xpath("//*[@class='backbone_modal-main']/article/table/tbody/tr[2]/td[2]/a").click()
            driver.find_element_by_xpath("//*[@id='drs']//ol/li[1]/label/input").click()
            driver.find_element_by_xpath("//*[@class='nav-tab-wrapper']/a[5]").click()
            driver.find_element_by_xpath("//*[@class='color-table']/tbody/tr[1]/td/button[1]").click()
            self.assertTrue(driver.find_element_by_xpath("//*[@class='color-table']/tbody/tr[3]"))
        except Exception as e:
            print("Color palette cannot be added. ")
            print(e)

    def testColorPaletteExist(self):
        try:
            wp_login()
            time.sleep(drs_page_load_wait)
            driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
            driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
            driver.find_element_by_xpath("//*[@id='menu-pages']/ul/li[3]/a").click()
            driver.find_element_by_id("drs-backbone_modal").click()
            driver.find_element_by_xpath("//*[@class='backbone_modal-main']/article/table/tbody/tr[2]/td[2]/a").click()
            driver.find_element_by_xpath("//*[@id='drs']//ol/li[1]/label/input").click()
            driver.find_element_by_xpath("//*[@class='nav-tab-wrapper']/a[5]").click()
            driver.find_element_by_xpath("//*[@class='color-table']/tbody/tr[1]/td/button[1]").click()
            driver.find_element_by_xpath("//*[@class='color-table']/tbody/tr[3]/td[4]/div").click()
            self.assertTrue(driver.find_elements_by_xpath("//*[@class='color-table']/tbody").size() == 2)
        except Exception as e:
            print("Color palette cannot be removed. ")
            print(e)



if __name__ == '__main__':
    unittest.main(verbosity=2)
