from pyvirtualdisplay import Display
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
from selenium.webdriver.common.action_chains import ActionChains
import inspect
import time

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
username = "team06"
password = "P@$$W0rd"

#Wordpress wp-admin URL
wordpress_url = "http://52.33.56.123/wp-login.php"

#DRS Wait tile for index to populate
drs_page_load_wait = 14

#Leaflet API Key
leaflet_api_key = "pk.eyJ1IjoiZGhhcmFtbWFuaWFyIiwiYSI6ImNpbTN0cjJmMTAwYmtpY2tyNjlvZDUzdXMifQ.8sUclClJc2zSBNW0ckJLOg"


#Leaflet Project Key
leaflet_project_key = "dharammaniar.pfnog3b9"

def create_driver():
    try:
        #For headless Unix Testing, will not work on Windows as XVFB is not supported
        #global display
        #display = Display(visible=0, size=(800, 600))
        #display.start()
        global driver
        driver = webdriver.Chrome()
        driver.set_window_size(1280,720)
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
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("ui-id-5").click()
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector(".drstk-include-map")[0].send_keys(Keys.SPACE)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("drstk_insert_map").click()
        print("PASS")
        close_driver_and_display()
    except Exception,e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

def test4():
    try:
        print("Testing to make sure several map's shortcode is enabled for selected DRS map items.")
        wp_add_page()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("ui-id-5").click()
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector(".drstk-include-map")[0].send_keys(Keys.SPACE)
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector(".drstk-include-map")[1].send_keys(Keys.SPACE)
        time.sleep(drs_page_load_wait)
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
        driver.get("http://52.33.56.123/maps-test-old-1")
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_xpath("//*[@id='map']/div[3]/div[2]/div[3]/img[1]")[0].click()
        time.sleep(drs_page_load_wait)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def test6():
    try:
        print("Testing to see if map elements where coordinates are specified can be zoomed in.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-old-1")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@title='Zoom in']").click()
        time.sleep(drs_page_load_wait)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def test7():
    try:
        print("Testing to see if map elements where coordinates are specified can be zoomed out.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-old-1")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@title='Zoom out']").click()
        time.sleep(drs_page_load_wait)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)


def test8():
    try:
        print("Testing to see if map elements where geographic locations are specified are populated and clickable.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-old-2")
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_xpath("//*[@id='map']/div[2]/div[2]/div[3]/img")[0].click()
        time.sleep(drs_page_load_wait)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def test9():
    try:
        print("Testing to see if map elements where geographic locations are specified can be zoomed in.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-old-2")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@title='Zoom in']").click()
        time.sleep(drs_page_load_wait)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def test10():
    try:
        print("Testing to see if map elements where geographic locations are specified can be zoomed out.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-old-2")
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@title='Zoom out']").click()
        time.sleep(drs_page_load_wait)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

#Tests for Sprint 3
def test11():
    try:
        print("Testing to make sure legend descriptions are generated.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-5']/button[2]").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='redlegend']").send_keys("red legend")
        driver.find_element_by_xpath("//*[@id='bluelegend']").send_keys("blue legend")
        driver.find_element_by_xpath("//*[@id='greenlegend']").send_keys("green legend")
        driver.find_element_by_xpath("//*[@id='yellowlegend']").send_keys("yellow legend")
        driver.find_element_by_xpath("//*[@id='orangelegend']").send_keys("orange legend")
        driver.find_element_by_xpath("//*[@id='drstk_insert_map']").click()
        print("PASS")
        close_driver_and_display()
    except Exception, e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

def test12():
    try:
        print("Testing to make sure you can set map display information.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-5']/button[2]").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[1]/div/label[1]/input").click()
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[1]/div/label[2]/input").click()
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[1]/div/label[3]/input").click()
        driver.find_element_by_xpath("//*[@id='drstk_insert_map']").click()
        print("PASS")
        close_driver_and_display()
    except Exception, e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

def test13():
    try:
        print("Testing to make sure you can set the color for selected items.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector(".drstk-include-map")[0].send_keys(Keys.SPACE)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").send_keys(Keys.ARROW_DOWN)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").send_keys(Keys.ENTER)
        driver.find_element_by_xpath("//*[@id='drstk_insert_map']").click()
        print("PASS")
        close_driver_and_display()
    except Exception, e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

def test14():
    try:
        print("Testing to make sure you can set the API Key.")
        wp_login()
        driver.get("http://52.33.56.123/wp-admin/options-general.php?page=drstk_admin_menu")
        driver.find_element_by_xpath("//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[2]/td/input").clear()
        driver.find_element_by_xpath("//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[2]/td/input").send_keys(leaflet_api_key)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='submit']").submit()
        time.sleep(drs_page_load_wait)
        print("PASS")
        close_driver_and_display()
    except Exception, e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

        def test14():
            try:
                print("Testing to make sure you can set the Project Key.")
                wp_login()
                driver.get("http://52.33.56.123/wp-admin/options-general.php?page=drstk_admin_menu")
                driver.find_element_by_xpath(
                    "//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[2]/td/input").clear()
                driver.find_element_by_xpath(
                    "//*[@id='wpbody-content']/div[2]/form/table[1]/tbody/tr[3]/td/input").send_keys(leaflet_project_key)
                time.sleep(drs_page_load_wait)
                driver.find_element_by_xpath("//*[@id='submit']").submit()
                time.sleep(drs_page_load_wait)
                print("PASS")
                close_driver_and_display()
            except Exception, e:
                print inspect.stack()[0][3] + " Failed with the following message:"
                print(e)


def test15():
    try:
        print("Testing to make sure you can select an item, legend, color and metadata and it will generate the shortcode.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector(".drstk-include-map")[0].send_keys(Keys.SPACE)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").send_keys(Keys.ARROW_DOWN)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").send_keys(Keys.ENTER)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='tabs-5']/button[2]").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='redlegend']").send_keys("red legend")
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[1]/div/label[1]/input").click()
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[1]/div/label[2]/input").click()
        driver.find_element_by_xpath("//*[@id='tabs-5']/div[1]/div/label[3]/input").click()
        driver.find_element_by_xpath("//*[@id='drstk_insert_map']").click()
        print("PASS")
        close_driver_and_display()
    except Exception, e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

def test16():
    try:
        print("Testing to see if multiple map elements where coordinates are specified are populated and clickable.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test")
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_xpath("//*[@id='map']/div[9]/div[1]/div[2]/button/span")[0].click()
        time.sleep(2)
        driver.find_elements_by_xpath("//*[@id='map']/div[9]/div[1]/div[2]/button/span")[0].click()
        time.sleep(2)
        driver.find_elements_by_xpath("//*[@id='map']/div[9]/div[1]/div[2]/button/span")[0].click()
        time.sleep(drs_page_load_wait)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)

def test17():
    try:
        print("Testing to see if multiple map elements where geolocations are specified are populated and clickable.")
        create_driver()
        driver.get("http://52.33.56.123/maps-test-2")
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_xpath("//*[@id='map']/div[10]/div[1]/div[2]/button/span")[0].click()
        time.sleep(2)
        driver.find_elements_by_xpath("//*[@id='map']/div[10]/div[1]/div[2]/button/span")[0].click()
        time.sleep(2)
        driver.find_elements_by_xpath("//*[@id='map']/div[10]/div[1]/div[2]/button/span")[0].click()
        time.sleep(drs_page_load_wait)
        print("PASS")
        close_driver_and_display()
    except Exception,e:
         print inspect.stack()[0][3] + " Failed with the following message:"
         print(e)


#Tests for Sprint 4
def test18():
    try:
        print("Testing to make sure you can set the color for selected items which now have icons.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_css_selector(".drstk-include-map")[0].send_keys(Keys.SPACE)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label//div/select").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").send_keys(Keys.ARROW_DOWN)
        time.sleep(drs_page_load_wait)
        driver.find_element_by_xpath("//*[@id='sortable-map-list']/li[1]/label/div/select").send_keys(Keys.ENTER)
        driver.find_element_by_xpath("//*[@id='drstk_insert_map']").click()
        print("PASS")
        close_driver_and_display()
    except Exception, e:
        print inspect.stack()[0][3] + " Failed with the following message:"
        print(e)

def test19():
    try:
        print("Testing to make sure you can ad a custom item.")
        wp_add_page()
        driver.find_element_by_xpath("//*[@id='ui-id-5']").click()
        time.sleep(drs_page_load_wait)
        driver.find_elements_by_xpath("//*[@id='open_add_custom_item'']").click()
        time.sleep(drs_page_load_wait)
        driver.find_element_by_id("custom_item_url")
        print("PASS")
        close_driver_and_display()
    except Exception, e:
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

def testsuite_spring3():
    print("Running Test Suite for Sprint 3...")
    test11()
    test12()
    test13()
    test14()
    test15()
    test16()
    test17()

def testsuite_spring4():
    print("Running Test Suite for Sprint 4...")
    test18()


#testsuite_sprint2()
#testsuite_spring3()
#testsuite_spring4()

test18()