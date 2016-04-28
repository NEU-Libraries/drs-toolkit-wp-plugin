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
current_dir = os.getcwd()

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
        try:
            wp_add_page()
            driver.find_element_by_xpath("//*[@id='ui-id-6']").click()
            time.sleep(drs_page_load_wait)
            self.assertTrue(driver.find_element_by_xpath(
            "//img[@src='https://repository.library.northeastern.edu/downloads/neu:180456?datastream_id=thumbnail_1']"))
        except Exception as e:
            print(inspect.stack()[0][3] + " Failed with the following message:")
            print(e)


    # DRS Timeline search functionality test
    def test2(self):
        try:
    #         print(
    #             "Testing to make sure search functionality is working and limiting results by keyword and if the item is a timeline item.")
            wp_add_page()
            search_keyword = "ralph"
            driver.find_element_by_id("ui-id-6").click()
            time.sleep(drs_page_load_wait)
            driver.find_element_by_id("search-timeline").send_keys(search_keyword)
            driver.find_element_by_id("search-button-timeline").click()
            time.sleep(4)
            self.assertTrue(driver.find_element_by_xpath(
                "//img[@src='https://repository.library.northeastern.edu/downloads/neu:180456?datastream_id=thumbnail_1']"))
        except Exception as e:
            print(inspect.stack()[0][3] + " Failed with the following message:")
            print(e)


    # DRS Timeline inserting 1 timeline shortcode test
    def test3(self):
        try:
            # print("Testing to make sure 1 timeline's shortcode is enabled for selected DRS map items.")
            wp_add_page()
            time.sleep(4)
            driver.find_element_by_id("ui-id-6").click()
            time.sleep(drs_page_load_wait)
            self.assertTrue(driver.find_elements_by_css_selector(".drstk-include-timeline")[0].send_keys(Keys.SPACE))
            time.sleep(4)
            # print("PASS")
            # close_driver_and_display()
        except Exception as e:
            print(inspect.stack()[0][3] + " Failed with the following message:")
            print(e)


    # def test4():
    #     try:
    #         print("Testing to make sure several timeline's shortcode is enabled for selected DRS timeline items.")
    #         wp_add_page()
    #         time.sleep(4)
    #         driver.find_element_by_id("ui-id-6").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_elements_by_css_selector(".drstk-include-timeline")[0].send_keys(Keys.SPACE)
    #         time.sleep(4)
    #         driver.find_elements_by_css_selector(".drstk-include-timeline")[1].send_keys(Keys.SPACE)
    #         time.sleep(4)
    #         driver.find_element_by_id("drstk_insert_timeline").click()
    #         time.sleep(4)
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    #
    # def test5():
    #     try:
    #         print("Testing to see if timeline elements are populated and navigable to next timeline.")
    #         create_driver()
    #         #driver.get("http://liblab.neu.edu/drstest/timeline-test/")
    #         driver.get(url)
    #         time.sleep(7)
    #         driver.find_element_by_css_selector(".tl-slidenav-content-container").click()
    #         time.sleep(4)
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    #
    # def test6():
    #     try:
    #         print("Testing to see if timeline date elements is obtained.")
    #         create_driver()
    #         #driver.get("http://liblab.neu.edu/drstest/timeline-test/")
    #         driver.get(url)
    #         time.sleep(4)
    #         driver.find_element_by_xpath("//*[@id=\"boston-boys-and-girls-club-photographs-marker\"]/div[2]/div").is_displayed()
    #         time.sleep(4)
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    #
    # def test7():
    #     try:
    #         print("Testing to see if timeline bar can be zoomed in.")
    #         create_driver()
    #         #driver.get("http://liblab.neu.edu/drstest/timeline-test/")
    #         driver.get(url)
    #         time.sleep(4)
    #         driver.find_element_by_xpath("//*[@id=\"timeline-embed\"]/div[3]/span[1]").click()
    #         time.sleep(4)
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    #
    # def test8():
    #     try:
    #         print("Testing to see if timeline bar can be zoomed out.")
    #         create_driver()
    #         #driver.get("http://liblab.neu.edu/drstest/timeline-test/")
    #         driver.get(url)
    #         time.sleep(4)
    #         driver.find_element_by_xpath("//*[@id=\"timeline-embed\"]/div[3]/span[2]").click()
    #         time.sleep(4)
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    #
    # def test9():
    #     try:
    #         print("Testing to see if timeline item image is present.")
    #         create_driver()
    #         #driver.get("http://liblab.neu.edu/drstest/timeline-test/")
    #         driver.get(url)
    #         time.sleep(4)
    #         driver.find_element_by_xpath("//*[@id=\"boston-boys-and-girls-club-photographs\"]/div[1]/div/div/div[1]/div[2]/div[1]/img").is_displayed()
    #         time.sleep(4)
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    # # Sprint 3
    #
    # def test10():
    #     try:
    #         print("Testing to make sure if Grouping checkbox is available")
    #         wp_add_page()
    #         time.sleep(4)
    #         driver.find_element_by_id("ui-id-6").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_elements_by_css_selector(".drstk-include-timeline")[0].send_keys(Keys.SPACE)
    #         time.sleep(4)
    #         driver.find_element_by_id("timeline_div-0")
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    # def test11():
    #     try:
    #         print("Testing to make sure if Start Boundary Textbox is displayed")
    #         wp_add_page()
    #         time.sleep(4)
    #         driver.find_element_by_id("ui-id-6").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
    #         time.sleep(4)
    #         if driver.find_element_by_id("start-date-boundary").is_displayed() :
    #             print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    # def test12():
    #     try:
    #         print("Testing to make sure if Start Boundary Textbox is displayed")
    #         wp_add_page()
    #         time.sleep(4)
    #         driver.find_element_by_id("ui-id-6").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
    #         time.sleep(4)
    #         if driver.find_element_by_id("end-date-boundary").is_displayed() :
    #             print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    # def test13():
    #     try:
    #         print("Testing to make sure if Boundary values are generated")
    #         wp_add_page()
    #         time.sleep(4)
    #         start_date = 1910
    #         end_date = 2000
    #         driver.find_element_by_id("ui-id-6").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
    #         time.sleep(4)
    #         driver.find_element_by_id("start-date-boundary").send_keys(start_date)
    #         driver.find_element_by_id("end-date-boundary").send_keys(end_date)
    #         time.sleep(4)
    #         driver.find_element_by_xpath("//*[@id='drstk_insert_timeline']").click()
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    # def test14():
    #     try:
    #         print("Testing to make sure legend descriptions are generated.")
    #         wp_add_page()
    #         driver.find_element_by_xpath("//*[@id='ui-id-6']").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='timeline_redlegend']").send_keys("red legend")
    #         driver.find_element_by_xpath("//*[@id='timeline_bluelegend']").send_keys("blue legend")
    #         driver.find_element_by_xpath("//*[@id='timeline_greenlegend']").send_keys("green legend")
    #         driver.find_element_by_xpath("//*[@id='timeline_yellowlegend']").send_keys("yellow legend")
    #         driver.find_element_by_xpath("//*[@id='timeline_orangelegend']").send_keys("orange legend")
    #         driver.find_element_by_xpath("//*[@id='drstk_insert_timeline']").click()
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception, e:
    #         print inspect.stack()[0][3] + " Failed with the following message:"
    #         print(e)
    #
    # def test15():
    #     try:
    #         print("Testing to make sure you can set the color for selected items.")
    #         wp_add_page()
    #         driver.find_element_by_xpath("//*[@id='ui-id-6']").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='drstk-timeline-increments']").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='drstk-timeline-increments']").send_keys(Keys.ARROW_DOWN)
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='drstk-timeline-increments']").send_keys(Keys.ARROW_DOWN)
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='drstk-timeline-increments']").send_keys(Keys.ENTER)
    #         driver.find_element_by_xpath("//*[@id='drstk_insert_timeline']").click()
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception, e:
    #         print inspect.stack()[0][3] + " Failed with the following message:"
    #         print(e)
    #
    # def test16():
    #     try:
    #         print("Testing to make sure you can set the color for selected items and generates a shortcode")
    #         wp_add_page()
    #         driver.find_element_by_xpath("//*[@id='ui-id-6']").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_elements_by_css_selector(".drstk-include-timeline")[0].send_keys(Keys.SPACE)
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='sortable-timeline-list']/li[1]/label/div/select").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='sortable-timeline-list']/li[1]/label/div/select").send_keys(Keys.ARROW_DOWN)
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='sortable-timeline-list']/li[1]/label/div/select").send_keys(Keys.ENTER)
    #         driver.find_element_by_xpath("//*[@id='drstk_insert_timeline']").click()
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception, e:
    #         print inspect.stack()[0][3] + " Failed with the following message:"
    #         print(e)
    #
    # def test17():
    #     try:
    #         print("Testing to make sure if the element is selected if it is inside the Boundary values")
    #         wp_add_page()
    #         time.sleep(4)
    #         start_date = 1910
    #         end_date = 2000
    #         driver.find_element_by_id("ui-id-6").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
    #         time.sleep(4)
    #         driver.find_element_by_id("start-date-boundary").send_keys(start_date)
    #         driver.find_element_by_id("end-date-boundary").send_keys(end_date)
    #         time.sleep(4)
    #         driver.find_elements_by_css_selector(".drstk-include-timeline")[0].send_keys(Keys.SPACE)
    #         time.sleep(4)
    #         driver.find_element_by_xpath("//*[@id='drstk_insert_timeline']").click()
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    # # Sprint 4
    # def test18():
    #     try:
    #         print("Testing to make sure specific legends are generated.")
    #         wp_add_page()
    #         driver.find_element_by_xpath("//*[@id='ui-id-6']").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='tabs-6']/button[2]").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='timeline_redlegend']").send_keys("red legend")
    #         time.sleep(4)
    #         driver.find_element_by_xpath("//*[@id='drstk_insert_timeline']").click()
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception, e:
    #         print inspect.stack()[0][3] + " Failed with the following message:"
    #         print(e)
    #
    # def test19():
    #
    #     URL = 'http://liblab.neu.edu/drstest/timeline-test-sprint-4/'
    #     try:
    #         print("Testing to see if Timeline item is present.")
    #         create_driver()
    #         driver.get(URL)
    #         time.sleep(4)
    #         driver.find_element_by_xpath("//*[@id='boston-boys-and-girls-club-photographs-3-marker']/div/div").is_displayed()
    #         time.sleep(4)
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    # def test20():
    #
    #     URL = 'http://liblab.neu.edu/drstest/timeline-test-sprint-4/'
    #     try:
    #         print("Testing to see if the Legend descriptions are displayed on the page")
    #         create_driver()
    #         driver.get(URL)
    #         time.sleep(4)
    #         driver.find_element_by_id("timeline-table").is_displayed()
    #         time.sleep(4)
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)
    #
    # def test21():
    #     try:
    #         print("Testing to make sure if Date is displayed")
    #         wp_add_page()
    #         time.sleep(4)
    #         driver.find_element_by_id("ui-id-6").click()
    #         time.sleep(drs_page_load_wait)
    #         driver.find_element_by_xpath("//*[@id='sortable-timeline-list']/li[1]/label/p").is_displayed()
    #         print("PASS")
    #         close_driver_and_display()
    #     except Exception as e:
    #         print(inspect.stack()[0][3] + " Failed with the following message:")
    #         print(e)

    # def testsuite_sprint2_timeline():
    #     print("Running Test Suite for Sprint 2...")
    #     test1()
    #     test2()
    #     test3()
    #     test4()
    #     test5()
    #     test6()
    #     test7()
    #     test8()
    #     test9()
    #
    # def testsuite_sprint3_timeline():
    #     print("Running Test Suite for Sprint 2...")
    #     test10()
    #     test11()
    #     test12()
    #     test13()
    #     test15()
    #     test16()
    #     test17()
    #
    # def testsuite_sprint4_timeline():
    #     print("Running Test Suite for Sprint 4...")
    #     test19()
    #     test20()
    #     test21()

    # testsuite_sprint2_timeline()
    # testsuite_sprint3_timeline()
    # testsuite_sprint4_timeline()
if __name__ == '__main__':
    unittest.main()
