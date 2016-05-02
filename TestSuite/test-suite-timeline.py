import unittest
import os
import inspect
import time
from pyvirtualdisplay import Display
from selenium import webdriver

# Packages Requirements for headless unix testing:
# sudo apt-get install libxss1 libappindicator1 libindicator7
# wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb

# sudo dpkg -i google-chrome*.deb
# sudo apt-get install -f
# sudo apt-get install xvfb
# sudo apt-get install unzip

# wget -N http://chromedriver.storage.googleapis.com/2.20/chromedriver_linux64.zip
# unzip chromedriver_linux64.zip
# chmod +x chromedriver

# sudo mv -f chromedriver /usr/local/share/chromedriver
# sudo ln -s /usr/local/share/chromedriver /usr/local/bin/chromedriver
# sudo ln -s /usr/local/share/chromedriver /usr/bin/chromedriver


# Login Credentials
from selenium.webdriver import ActionChains
from selenium.webdriver.common.keys import Keys

username = "testuser"
password = "P@$$W0rd"
url = "http://liblab.neu.edu/drstest/timeline-test-colorcode/"

# Wordpress wp-admin URL
wordpress_url = "http://liblab.neu.edu/drstest/wp-login.php"

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
    try:
        wp_login()
        driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
        driver.find_element_by_xpath("//*[@id='menu-pages']/ul/li[3]/a").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("insert-drs").click()
    except Exception as e:
        print("Exception produced when creating new page. Error is: ")
        print(e)


class TestTimelineFunctions(unittest.TestCase):

    def setUp(self):
        try:
            # For headless Unix Testing, will not work on Windows as XVFB is not supported
            global driver
            driver = webdriver.Firefox()
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
        driver.find_element_by_xpath("//*[@id='ui-id-6']").click()
        time.sleep(drs_page_load_wait)
        self.assertTrue(driver.find_element_by_xpath(
        "//img[@src='https://repository.library.northeastern.edu/downloads/neu:180456?datastream_id=thumbnail_1']"))


    # DRS Timeline search functionality test
    def test2(self):
        # print("Testing to make sure search functionality is working and limiting results by keyword and if the item is a timeline item.")
        wp_add_page()
        search_keyword = "ralph"
        driver.find_element_by_id("ui-id-6").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("search-timeline").send_keys(search_keyword)
        driver.find_element_by_id("search-button-timeline").click()
        time.sleep(4)
        self.assertTrue(driver.find_element_by_xpath(
                "//img[@src='https://repository.library.northeastern.edu/downloads/neu:180456?datastream_id=thumbnail_1']"))


    # DRS Timeline inserting 1 timeline shortcode test
    def test3(self):
        # print("Testing to make sure 1 timeline's shortcode is enabled for selected DRS map items.")
        wp_add_page()
        time.sleep(4)
        driver.find_element_by_id("ui-id-6").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        elem = driver.find_element_by_css_selector("#sortable-timeline-list #drstile-1")
        elem.click()
        time.sleep(drs_page_load_wait)
        pid = elem.get_attribute("value")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("drstk_insert_timeline").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("content-html").click()
        time.sleep(drs_page_load_wait)
        this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
        self.assertIn(pid, this_content)



    def test4(self):
        # print("Testing to make sure several timeline's shortcode is enabled for selected DRS timeline items.")
        wp_add_page()
        time.sleep(4)
        driver.find_element_by_id("ui-id-6").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector("#sortable-timeline-list .drstk-include-timeline")[0].click()
        time.sleep(drs_page_load_wait)
        elem2 = driver.find_elements_by_css_selector("#sortable-timeline-list .drstk-include-timeline")[1]
        elem2.click()
        pid2 = elem2.get_attribute("value")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("drstk_insert_timeline").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("content-html").click()
        time.sleep(drs_page_load_wait)
        this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
        self.assertIn(pid2, this_content)


    def test5(self):
        # print("Testing to see if timeline elements are populated and navigable to next timeline.")
        driver.get(url)
        time.sleep(7)
        old_style = driver.find_element_by_css_selector(".tl-slider-container.tlanimate").get_attribute("style")
        driver.find_element_by_css_selector(".tl-slidenav-content-container").click()
        time.sleep(4)
        new_style = driver.find_element_by_css_selector(".tl-slider-container.tlanimate").get_attribute("style")
        self.assertNotEqual(old_style, new_style)


    def test6(self):
        # print("Testing to see if timeline date elements is obtained.")
        driver.get(url)
        time.sleep(4)
        self.assertTrue(driver.find_element_by_xpath("//*[@id=\"_1949-1950-roxbury-clubhouse-basketball-team-posing-with-their-trophy\"]/div/div/div").is_displayed())


    def test7(self):
        # print("Testing to see if timeline bar can be zoomed in.")
        driver.get(url)
        time.sleep(4)
        style_before = driver.find_element_by_css_selector(".tl-timenav-slider").get_attribute("style")
        driver.find_element_by_xpath("//*[@id=\"timeline-embed\"]/div[3]/span[1]").click()
        style_after = driver.find_element_by_css_selector(".tl-timenav-slider").get_attribute("style")
        self.assertNotEqual(style_before, style_after)


    def test8(self):
        # print("Testing to see if timeline bar can be zoomed out.")
        driver.get(url)
        time.sleep(4)
        style_before = driver.find_element_by_css_selector(".tl-timenav-slider").get_attribute("style")
        driver.find_element_by_xpath("//*[@id=\"timeline-embed\"]/div[3]/span[1]").click()
        style_after = driver.find_element_by_css_selector(".tl-timenav-slider").get_attribute("style")
        self.assertNotEqual(style_before, style_after)



    def test9(self):
        # print("Testing to see if timeline item image is present.")
        driver.get(url)
        time.sleep(4)
        self.assertTrue(driver.find_element_by_xpath("//*[@id=\"_1949-1950-roxbury-clubhouse-basketball-team-posing-with-their-trophy\"]/div[1]/div/div/div[1]/div[2]/div[1]/img").is_displayed())


    # Sprint 3
    def test10(self):
        # print("Testing to make sure if Grouping checkbox is available")
        wp_add_page()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("ui-id-6").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_css_selector("#sortable-timeline-list .drstk-include-timeline[0]").click()
        time.sleep(drs_page_load_wait)
        self.assertTrue(driver.find_element_by_css_selector("#sortable-timeline-list .timeline_group_selection-0"))


    def test11(self):
        # print("Testing to make sure if Start Boundary Textbox is displayed")
        wp_add_page()
        time.sleep(4)
        driver.find_element_by_id("ui-id-6").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
        time.sleep(4)
        self.assertTrue(driver.find_element_by_id("start-date-boundary").is_displayed())

    def test12(self):
        # print("Testing to make sure if Start Boundary Textbox is displayed")
        wp_add_page()
        time.sleep(4)
        driver.find_element_by_id("ui-id-6").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
        time.sleep(4)
        self.assertTrue(driver.find_element_by_id("end-date-boundary").is_displayed())

    def test13(self):
        # print("Testing to make sure if item is outside boundary dates or non-numeric, it triggers alert")
        wp_add_page()
        time.sleep(4)
        start_date = 2000
        end_date = 2010
        driver.find_element_by_id("ui-id-6").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
        time.sleep(drs_page_load_wait)
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector("#sortable-timeline-list .drstk-include-timeline")[0].click()
        pid = driver.find_elements_by_css_selector("#sortable-timeline-list .drstk-include-timeline")[0].get_attribute("value")
        driver.find_element_by_id("start-date-boundary").send_keys(start_date)
        driver.find_element_by_id("end-date-boundary").send_keys(end_date)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("drstk_insert_timeline").click()
        alert = driver.switch_to_alert()
        self.assertIn("out of the specified boundary dates", alert.text)
        alert.accept()
        start_date = "start"
        driver.find_element_by_id("start-date-boundary").send_keys(start_date)
        driver.find_element_by_id("drstk_insert_timeline").click()
        alert = driver.switch_to_alert()
        self.assertIn("is not numeric", alert.text)

    def test14(self):
        # print("Testing to make sure legend descriptions are generated.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-6']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='timeline_redlegend']").send_keys("red legend")
        driver.find_element_by_xpath("//*[@id='timeline_bluelegend']").send_keys("blue legend")
        driver.find_element_by_xpath("//*[@id='timeline_greenlegend']").send_keys("green legend")
        driver.find_element_by_xpath("//*[@id='timeline_yellowlegend']").send_keys("yellow legend")
        driver.find_element_by_xpath("//*[@id='timeline_orangelegend']").send_keys("orange legend")
        driver.find_element_by_css_selector("#sortable-timeline-list #drstile-0").click()
        pid = driver.find_element_by_css_selector("#sortable-timeline-list #drstile-0").get_attribute("value")
        el = driver.find_element_by_css_selector("#sortable-timeline-list #timeline_div-0 .timeline_group_selection-0")
        for option in el.find_elements_by_tag_name('option'):
            if option.text == 'Red':
                option.click() # select() in earlier versions of webdriver
                break
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("drstk_insert_timeline").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("content-html").click()
        time.sleep(drs_page_load_wait)
        this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
        self.assertIn('red_desc="red legend"', this_content)
        self.assertIn('red_id="'+pid+'"', this_content)

    def test15(self):
        # print("Testing to make sure you can set the scale increments")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-6']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_css_selector("#sortable-timeline-list #drstile-0").click()
        driver.find_element_by_xpath("//*[@id='drstk-timeline-increments']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drstk-timeline-increments']").send_keys(Keys.ARROW_DOWN)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drstk-timeline-increments']").send_keys(Keys.ARROW_DOWN)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='drstk-timeline-increments']").send_keys(Keys.ENTER)
        driver.find_element_by_id("drstk_insert_timeline").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("content-html").click()
        time.sleep(drs_page_load_wait)
        this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
        self.assertIn("increments=", this_content)

    def test17(self):
        # print("Testing to make sure if the element is selected if it is inside the Boundary values")
        wp_add_page()
        time.sleep(4)
        start_date = 1910
        end_date = 2000
        driver.find_element_by_id("ui-id-6").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("start-date-boundary").send_keys(start_date)
        driver.find_element_by_id("end-date-boundary").send_keys(end_date)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_css_selector("#sortable-timeline-list #drstile-0").click()
        pid = driver.find_element_by_css_selector("#sortable-timeline-list #drstile-0").get_attribute("value")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("drstk_insert_timeline").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("content-html").click()
        time.sleep(drs_page_load_wait)
        this_content = driver.find_element_by_xpath("//*[@id=\"wp-content-editor-container\"]/textarea").get_attribute("value")
        self.assertIn(pid, this_content)


    # Sprint 4

    def test18(self):
        URL = 'http://liblab.neu.edu/drstest/timeline-test-sprint-4/'
        # print("Testing to see if Timeline item is present.")
        driver.get(URL)
        time.sleep(4)
        self.assertTrue(driver.find_element_by_xpath("//*[@id='_1949-1950-roxbury-clubhouse-basketball-team-posing-with-their-trophy']/div/div").is_displayed())


    def test19(self):
        URL = 'http://liblab.neu.edu/drstest/timeline-test-sprint-4/'
        # print("Testing to see if the Legend descriptions are displayed on the page")
        driver.get(URL)
        time.sleep(4)
        self.assertTrue(driver.find_element_by_id("timeline-table").is_displayed())


    def test20(self):
        # print("Testing to make sure if Date is displayed")
        wp_add_page()
        time.sleep(4)
        driver.find_element_by_id("ui-id-6").click()
        time.sleep(drs_page_load_wait)
        self.assertTrue(driver.find_element_by_xpath("//*[@id='sortable-timeline-list']/li[1]/label/p").is_displayed())


if __name__ == '__main__':
    unittest.main(verbosity=2)
