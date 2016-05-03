import unittest
import os
import inspect
import time
from pyvirtualdisplay import Display
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
from selenium.webdriver.common.action_chains import ActionChains

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
username = "testuser"
password = "P@$$W0rd"

#Wordpress wp-admin URL
wordpress_url = "http://liblab.neu.edu/drstest/wp-login.php"

#DRS Wait tile for index to populate
drs_page_load_wait = 14

#Leaflet API Key
leaflet_api_key = "pk.eyJ1IjoiZGhhcmFtbWFuaWFyIiwiYSI6ImNpbTN0cjJmMTAwYmtpY2tyNjlvZDUzdXMifQ.8sUclClJc2zSBNW0ckJLOg"


#Leaflet Project Key
leaflet_project_key = "dharammaniar.pfnog3b9"

def wp_login():
    try:
        driver.get(wordpress_url)
        driver.find_element_by_id("user_login").send_keys(username)
        driver.find_element_by_id("user_pass").send_keys(password)
        driver.find_element_by_id("wp-submit").click()
    except Exception,e:
        print("Exception produced when logging into wp-admin. Error is: ")
        print(e)


def wp_add_page():
    attempt = 0
    try:
        wp_login()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
        driver.find_element_by_xpath("//*[@id='menu-pages']/ul/li[3]/a").click()
        driver.find_element_by_id("insert-drs").click()
        attempt = attempt + 1
    except Exception as e:
        if attempt < 3:
            wp_add_page()
        print("Exception produced when creating new page. Error is: ")
        print(e)

class TestMapFunctions(unittest.TestCase):
    def setUp(self):
        try:
            # For headless Unix Testing, will not work on Windows as XVFB is not supported
            global driver
            driver = webdriver.Firefox()
            driver.set_window_size(1280,720)
        except Exception as e:
            print("Error produced when setting webdriver and/or XVFB display.")
            print(e)


    def tearDown(self):
        try:
            driver.quit()
        except Exception as e:
            print("Error produced when closing driver and display.")
            print(e)



    #DRS Map index test
    def test1(self):
        # print("Testing to make sure index for DRS Map items is generated.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        self.assertTrue(driver.find_element_by_xpath("//img[@src='https://repository.library.northeastern.edu/downloads/neu:180456?datastream_id=thumbnail_1']"))

    #DRS Map search functionality test
    def test2(self):
        # print("Testing to make sure search functionality is working and limiting results by keyword and if the item is a map item.")
        wp_add_page()
        search_keyword = "ralph"
        driver.find_element_by_id("ui-id-5").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("search-map").send_keys(search_keyword)
        driver.find_element_by_id("search-button-map").click()
        time.sleep(drs_page_load_wait)
        self.assertTrue(driver.find_element_by_xpath("//img[@src='https://repository.library.northeastern.edu/downloads/neu:180456?datastream_id=thumbnail_1']"))

    # #DRS Map inserting 1 map shortcode test
    # def test3(self):
    #     # print("Testing to make sure 1 map's shortcode is enabled for selected DRS map items.")
    #     wp_add_page()
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_id("ui-id-5").click()
    #     time.sleep(drs_page_load_wait)
    #     # driver.find_elements_by_css_selector(".drstk-include-map")[0].send_keys(Keys.SPACE)
    #     elem = driver.find_elements_by_css_selector("#sortable-map-list .drstk-include-map")[0]
    #     elem.click()
    #     pid = elem.get_attribute("value")
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_id("drstk_insert_map").click()
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_id("content-html").click()
    #     time.sleep(drs_page_load_wait)
    #     this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
    #     self.assertIn(pid, this_content)
    #
    # def test4(self):
    #     # print("Testing to make sure several map's shortcode is enabled for selected DRS map items.")
    #     wp_add_page()
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_id("ui-id-5").click()
    #     time.sleep(drs_page_load_wait)
    #     elem1 = driver.find_elements_by_css_selector(".drstk-include-map")[0]
    #     elem1.click()
    #     pid1 = elem1.get_attribute("value")
    #     time.sleep(drs_page_load_wait)
    #     elem2 = driver.find_elements_by_css_selector(".drstk-include-map")[1]
    #     elem2.click()
    #     pid2 = elem2.get_attribute("value")
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_id("drstk_insert_map").click()
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_id("content-html").click()
    #     time.sleep(drs_page_load_wait)
    #     this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
    #     self.assertIn(pid1, this_content)
    #     self.assertIn(pid2, this_content)
    #
    # def test5(self):
    #     # print("Testing to see if map elements where coordinates are specified are populated and clickable.")
    #     driver.get("http://liblab.neu.edu/drstest/maps-test-coord")
    #     time.sleep(drs_page_load_wait)
    #     driver.find_elements_by_css_selector(".leaflet-marker-icon")[0].click()
    #     self.assertTrue(driver.find_elements_by_css_selector(".leaflet-marker-icon")[0])

    def test6(self):
        # print("Testing to see if map elements where coordinates are specified can be zoomed in.")
        driver.get("http://liblab.neu.edu/drstest/maps-test-coord")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@title='Zoom in']").click()
        time.sleep(drs_page_load_wait)
        #leaflet tests the functionality of the zoom buttons

    def test7(self):
        # print("Testing to see if map elements where coordinates are specified can be zoomed out.")
        driver.get("http://liblab.neu.edu/drstest/maps-test-coord")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@title='Zoom out']").click()
        time.sleep(drs_page_load_wait)
        #leaflet tests the functionality of the zoom buttons

    # def test8(self):
    #     # print("Testing to see if map elements where geographic locations are specified are populated and clickable.")
    #     driver.get("http://liblab.neu.edu/drstest/maps-test-geo/")
    #     time.sleep(drs_page_load_wait)
    #     driver.find_elements_by_css_selector(".leaflet-marker-icon")[0].click()
    #     self.assertTrue(driver.find_elements_by_css_selector(".leaflet-marker-icon")[0])

    def test9(self):
        # print("Testing to see if map elements where geographic locations are specified can be zoomed in.")
        driver.get("http://liblab.neu.edu/drstest/maps-test-geo/")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@title='Zoom in']").click()
        time.sleep(drs_page_load_wait)
        #leaflet tests the functionality of the zoom buttons

    def test10(self):
        # print("Testing to see if map elements where geographic locations are specified can be zoomed out.")
        driver.get("http://liblab.neu.edu/drstest/maps-test-geo/")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@title='Zoom out']").click()
        time.sleep(drs_page_load_wait)
        #leaflet tests the functionality of the zoom buttons

    #Tests for Sprint 3
    # def test11(self):
    #     # print("Testing to make sure legend descriptions are generated.")
    #     wp_add_page()
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_xpath("//*[@id='tabs-5']/button[2]").click()
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_xpath("//*[@id='redlegend']").send_keys("red legend")
    #     driver.find_element_by_xpath("//*[@id='bluelegend']").send_keys("blue legend")
    #     driver.find_element_by_xpath("//*[@id='greenlegend']").send_keys("green legend")
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_xpath("//*[@id='yellowlegend']").send_keys("yellow legend")
    #     driver.find_element_by_xpath("//*[@id='orangelegend']").send_keys("orange legend")
    #     driver.find_elements_by_css_selector("#sortable-map-list .drstk-include-map")[0].click()
    #     pid = driver.find_elements_by_css_selector("#sortable-map-list .drstk-include-map")[0].get_attribute("value")
    #     el = driver.find_element_by_css_selector("#sortable-map-list li:first div select")
    #     for option in el.find_elements_by_tag_name('option'):
    #         if option.text == 'Red':
    #             option.click() # select() in earlier versions of webdriver
    #             break
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_id("drstk_insert_map").click()
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_id("content-html").click()
    #     time.sleep(drs_page_load_wait)
    #     this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
    #     self.assertIn('red_legend_desc="red legend"', this_content)
    #     self.assertIn('red_id="'+pid+'"', this_content)

    def test12(self):
        # print("Testing to make sure you can set map display information.")
        wp_add_page()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-5']/button[2]").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[2]/label[2]/div/label[1]/input").click()
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[2]/label[2]/div/label[2]/input").click()
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[2]/label[2]/div/label[3]/input").click()
        driver.find_element_by_xpath("//*[@id='drstk_insert_map']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("content-html").click()
        time.sleep(drs_page_load_wait)
        this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
        self.assertIn('metadata="Creator,Contributor,Date created,Abstract/Description"', this_content)


    def test13(self):
        # print("Testing to make sure you can set the API Key.")
        wp_login()
        driver.get("http://liblab.neu.edu/drstest/wp-admin/options-general.php?page=drstk_admin_menu")
        time.sleep(4)
        driver.find_element_by_xpath("//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[3]/td/input").clear()
        driver.find_element_by_xpath("//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[3]/td/input").send_keys(leaflet_api_key)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='submit']").submit()
        time.sleep(drs_page_load_wait)
        driver.get("http://liblab.neu.edu/drstest/wp-admin/options-general.php?page=drstk_admin_menu")
        self.assertEqual(driver.find_element_by_xpath("//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[3]/td/input").get_attribute("value"), leaflet_api_key)

    # def test14(self):
    #     # print("Testing to make sure you can set the Project Key.")
    #     wp_login()
    #     driver.get("http://liblab.neu.edu/drstest/wp-admin/options-general.php?page=drstk_admin_menu")
    #     time.sleep(4)
    #     driver.find_element_by_xpath("//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[4]/td/input").clear()
    #     driver.find_element_by_xpath("//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[4]/td/input").send_keys(leaflet_project_key)
    #     time.sleep(drs_page_load_wait)
    #     driver.find_element_by_xpath("//*[@id='submit']").submit()
    #     time.sleep(drs_page_load_wait)
    #     driver.get("http://liblab.neu.edu/drstest/wp-admin/options-general.php?page=drstk_admin_menu")
    #     self.assertEqual(driver.find_element_by_xpath("//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[4]/td/input").get_attribute("value"), leaflet_project_key)


    # def test15(self):
    #     # print("Testing to see if multiple map elements where coordinates are specified are populated and clickable.")
    #     driver.get("http://liblab.neu.edu/drstest/maps-test/")
    #     time.sleep(drs_page_load_wait)
    #     driver.find_elements_by_css_selector(".leaflet-marker-icon")[0].click()
    #     driver.find_elements_by_css_selector(".leaflet-marker-icon .leaflet-marker-icon")[0].click()
    #     popup = driver.find_element_by_css_selector(".leaflet_popup_pane")
    #     self.assertTrue(popup.is_displayed())
    #
    # def test16(self):
    #     # print("Testing to see if multiple map elements where geolocations are specified are populated and clickable.")
    #     driver.get("http://liblab.neu.edu/drstest/maps-test-2")
    #     time.sleep(drs_page_load_wait)
    #     driver.find_elements_by_css_selector(".leaflet-marker-icon")[0].click()
    #     popup = driver.find_element_by_css_selector(".leaflet_popup_pane")
    #     self.assertTrue(popup.is_displayed())


    #Tests for Sprint 4
    def test17(self):
        # print("Testing to make sure you can add a custom map item.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='open_add_custom_item']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("custom_item_url").send_keys("https://urlhere.com")
        driver.find_element_by_id("custom_item_title").send_keys("This is a cool title")
        driver.find_element_by_id("custom_item_description").send_keys("This is a cool description")
        driver.find_element_by_id("custom_item_location").send_keys("Boston,MA")
        driver.find_element_by_xpath("//*[@id='custom_item_color_grouping']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='custom_item_color_grouping']").send_keys(Keys.ARROW_DOWN)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='custom_item_color_grouping']").send_keys(Keys.ENTER)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='submit_custom_item']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drstk_insert_map']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("content-html").click()
        time.sleep(drs_page_load_wait)
        this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
        self.assertIn("This is a cool title", this_content)
        self.assertIn("https://urlhere.com", this_content)
        self.assertIn("This is a cool description", this_content)
        self.assertIn("Boston,MA", this_content)

    def test18(self):
        # print("Testing to make sure you can select an item, legend, color and metadata and it will generate the shortcode for both custom and exiting items.")
        wp_add_page()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector(".drstk-include-map")[0].send_keys(Keys.SPACE)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label//div/select").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").send_keys(Keys.ARROW_DOWN)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").send_keys(Keys.ENTER)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='open_add_custom_item']").click()
        driver.find_element_by_id("custom_item_url").send_keys("https://urlhere.com")
        driver.find_element_by_id("custom_item_title").send_keys("This is a cool title")
        driver.find_element_by_id("custom_item_description").send_keys("This is a cool description")
        driver.find_element_by_id("custom_item_location").send_keys("Boston,MA")
        driver.find_element_by_xpath("//*[@id='custom_item_color_grouping']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='custom_item_color_grouping']").send_keys(Keys.ARROW_DOWN)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='custom_item_color_grouping']").send_keys(Keys.ENTER)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='submit_custom_item']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-5']/button[2]").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='redlegend']").send_keys("red legend")
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[2]/label[2]/div/label[1]/input").click()
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[2]/label[2]/div/label[2]/input").click()
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[2]/label[2]/div/label[3]/input").click()
        driver.find_element_by_xpath("//*[@id='drstk_insert_map']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("content-html").click()
        time.sleep(drs_page_load_wait)
        this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
        self.assertIn("This is a cool title", this_content)
        self.assertIn("https://urlhere.com", this_content)
        self.assertIn("This is a cool description", this_content)
        self.assertIn("Boston,MA", this_content)
        self.assertIn("red legend", this_content)
        self.assertIn("metadata=", this_content)


    # def test19(self):
    #     # print("Testing to see if multiple map elements where some items are custom, and some are not,  are populated and clickable.")
    #     driver.get("http://liblab.neu.edu/drstest/maps-test-2")
    #     time.sleep(drs_page_load_wait)
    #     driver.find_elements_by_css_selector(".leaflet-marker-icon")[0].click()
    #     popup = driver.find_element_by_css_selector(".leaflet_popup_pane")
    #     self.assertTrue(popup.is_displayed())



if __name__ == '__main__':
    unittest.main(verbosity=2)
