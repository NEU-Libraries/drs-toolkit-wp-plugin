from pyvirtualdisplay import Display
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
import inspect
import time

#Packages Requirements for headless unix testing:
#apt-get install xvfb
#apt-get remove iceweasel
#echo -e "\ndeb http://downloads.sourceforge.net/project/ubuntuzilla/mozilla/apt all main" | tee -a /etc/apt/sources.list > /dev/null
#apt-key adv --recv-keys --keyserver keyserver.ubuntu.com C1289A29
#apt-get update
#apt-get install firefox-mozilla-build
#apt-get install libdbus-glib-1-2
#apt-get install libgtk2.0-0
#apt-get install libasound2

#Python library requirements
#pip install pyvirtualdisplay
#pip install selenium
#NOTE: If pip is not installed, run apt-get install python-pip OR:
#curl --silent --show-error --retry 5 https://raw.github.com/pypa/pip/master/contrib/get-pip.py | sudo python

#Login Credentials
username = "team06"
password = "P@$$W0rd"

#Wordpress wp-admin URL
wordpress_url = "http://52.33.56.123/wp-login.php"

#DRS Wait tile for index to populate
drs_page_load_wait = 14

def create_driver():
    try:
        #For headless Unix Testing, will not work on Windows as XVFB is not supported
        #global display
        #display.start()
        global driver
        driver = webdriver.Firefox()
    except Exception,e:
        print("Error produced when setting webdriver and/or XVFB display.")
        print(e)

def close_driver_and_display():
    try:
        driver.quit()
        #display.stop()
    except Exception,e:
        print("Error produced when closing driver and display.")
        print(e)


def wp_login():
    try:
        create_driver() #creates the driver so we can use it
        driver.get(wordpress_url)
        driver.find_element_by_id("user_login").send_keys(username)
        driver.find_element_by_id("user_pass").send_keys(password)
        driver.find_element_by_id("wp-submit").click()
        #print("Login completed successfully")
    except Exception,e:
        print("Exception produced when logging into wp-admin. Error is: ")
        print(e)


def wp_add_page():
    try:
        wp_login()
        driver.find_element_by_xpath("//*[@id='menu-pages']/a/div[3]").click()
        driver.find_element_by_xpath("//*[@id='menu-pages']/ul/li[3]/a").click()
        driver.find_element_by_id("insert-drs").click()
    except Exception,e:
        print("Exception produced when creating new page. Error is: ")
        print(e)

#DRS Map index test
def test1():
    try:
        print("Testing to make sure index for DRS Map items is generated.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//img[@src='https://repository.library.northeastern.edu/downloads/neu:180456?datastream_id=thumbnail_1']")
        print("PASS")
        close_driver_and_display()
    except Exception,e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

#DRS Map search functionality test
def test2():
    try:
        print("Testing to make sure search functionality is working and limiting results by keyword and if the item is a map item.")
        wp_add_page()
        search_keyword = "ralph"
        driver.find_element_by_id("ui-id-5").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("search-map").send_keys(search_keyword)
        driver.find_element_by_id("search-button-map").click()
        time.sleep(4)
        driver.find_element_by_xpath("//img[@src='https://repository.library.northeastern.edu/downloads/neu:180456?datastream_id=thumbnail_1']")
        print("PASS")
        close_driver_and_display()
    except Exception,e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)
#DRS Map inserting 1 map shortcode test
def test3():
    try:
        print("Testing to make sure 1 map's shortcode is enabled for selected DRS map items.")
        wp_add_page()
        driver.find_element_by_id("ui-id-5").click()
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector(".drstk-include-map")[0].click()
        driver.find_element_by_id("drstk_insert_map").click()
        time.sleep(4)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

def test4():
    try:
        print("Testing to make sure several map's shortcode is enabled for selected DRS map items.")
        wp_add_page()
        driver.find_element_by_id("ui-id-5").click()
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector(".drstk-include-map")[0].click()
        driver.find_elements_by_css_selector(".drstk-include-map")[1].click()
        driver.find_element_by_id("drstk_insert_map").click()
        time.sleep(4)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

def test5():
    try:
        print("Testing to see if map elements where coordinates are specified are populated and clickable.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-co-ordinates")
        time.sleep(4)
        driver.find_elements_by_xpath("//img[@src='http://52.33.56.123/wp-content/plugins/drs-tk/assets/js/leaflet/images/marker-icon.png']")[0].click()
        time.sleep(4)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def test6():
    try:
        print("Testing to see if map elements where coordinates are specified can be zoomed in.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-co-ordinates")
        time.sleep(4)
        driver.find_element_by_xpath("//*[@title='Zoom in']").click()
        time.sleep(4)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def test7():
    try:
        print("Testing to see if map elements where coordinates are specified can be zoomed out.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-co-ordinates")
        time.sleep(4)
        driver.find_element_by_xpath("//*[@title='Zoom out']").click()
        time.sleep(4)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)


def test8():
    try:
        print("Testing to see if map elements where geographic locations are specified are populated and clickable.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-geographic")
        time.sleep(4)
        driver.find_elements_by_xpath("//img[@src='http://52.33.56.123/wp-content/plugins/drs-tk/assets/js/leaflet/images/marker-icon.png']")[0].click()
        time.sleep(4)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def test9():
    try:
        print("Testing to see if map elements where geographic locations are specified can be zoomed in.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-geographic")
        time.sleep(4)
        driver.find_element_by_xpath("//*[@title='Zoom in']").click()
        time.sleep(4)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def test10():
    try:
        print("Testing to see if map elements where geographic locations are specified can be zoomed out.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-geographic")
        time.sleep(4)
        driver.find_element_by_xpath("//*[@title='Zoom out']").click()
        time.sleep(4)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def testsuite_sprint2():
    print("Running Test Suite for Sprint 2...")
    test1()
    test2()
    test3()
    test4()
    test5()
    test6()
    test7()
    test8()
    test9()
    test10()

testsuite_sprint2()