/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

import com.gargoylesoftware.htmlunit.BrowserVersion;
import static com.gargoylesoftware.htmlunit.BrowserVersion.FIREFOX_45;
import java.util.ArrayList;
import java.util.List;
import static org.hamcrest.CoreMatchers.not;
import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;
import static org.junit.Assert.*;
import org.junit.matchers.JUnitMatchers;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.htmlunit.HtmlUnitDriver;

/**
 *
 * @author Kartik
 */
public class facets_testing {
    String username="achinta";
    String password = "admin";
    
//    System.setProperty("webdriver.chrome.driver", "C:\\Users\\Kartik\\Downloads\\chromedriver.exe");
//    WebDriver driver = new ChromeDriver();
    
    HtmlUnitDriver driver = new HtmlUnitDriver(FIREFOX_45);
    
    String wordpress_url = "http://54.172.213.115/wordpress/wp-login.php";
    String page_url = "http://54.172.213.115/wordpress/25nov/";
//    public facets_testing() {
//    }
//    
//    @BeforeClass
//    public static void setUpClass() {
//    }
//    
//    @AfterClass
//    public static void tearDownClass() {
//    }
//    
//    @Before
//    public void setUp() {
//    }
//    
//    @After
//    public void tearDown() {
//    }
//    @Before
    public void wp_login(){
        WebDriver driver = new ChromeDriver();
        try{
            driver.get(wordpress_url);
            driver.findElement(By.id("user_login")).sendKeys(username);
            driver.findElement(By.id("user_pass")).sendKeys(password);
            driver.findElement(By.id("wp-submit")).click();
        }
        catch(Exception e){
            System.out.println("Exception produced when logging into wp-admin. Error is: " + e);
        }
    }
    
    @Test
    public void testCreatorFacet(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            String element_text = driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[1]/b")).getText();
            System.out.println(driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[1]/b")).getText());
            assertEquals(element_text, "Creator");
        }
        catch(Exception e){
            System.out.println("Creator facet is not present.Error : " +e);
        }
    }
    @Test
    public void testCreatorLinkPresent(){
        List<String> creator_name = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> creator_list  = driver.findElements(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[2]/a/div[1]"));
            for(WebElement creator : creator_list){
                creator_name.add(creator.getText());
            }
            assertThat(creator_name, JUnitMatchers.hasItem("Shwachman, Irene"));
        }
        catch(Exception e){
            System.out.println("Creator name is not present.Error : " +e);
        }
    }
    
    @Test
    public void testCreatorLinkNotPresent(){
        List<String> creator_name = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> creator_list  = driver.findElements(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[2]/a/div[1]"));
            for(WebElement creator : creator_list){
                creator_name.add(creator.getText());
            }
            assertThat(creator_name, not(JUnitMatchers.hasItem("abcdef")));
        }
        catch(Exception e){
            System.out.println("Creator name is falsely present.Error : " +e);
        }
    }
    
    @Test
    public void testCreatorMoreButton(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            String element_text = driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[2]/button")).getText();
            System.out.println(driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[2]/button")).getText());
            assertEquals(element_text, "More Creators");
        }
        catch(Exception e){
            System.out.println("More Creators button is not present.Error : " +e);
        }
    }
    @Test
    public void testCreatorMoreButtonWorking(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[2]/button")).click();
            WebElement more_creators_model = driver.findElement(By.xpath("//div[@id=\"drs_modal_creator_sim\"]"));
            assertTrue(more_creators_model.isEnabled());
        }
        catch(Exception e){
            System.out.println("More Creators button is not working.Error : " +e);
        }
    }   
    @Test
    public void testCreatorFiltering(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            String facet_name = driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[1]/b")).getText();
            String facet_text = driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[2]/a[1]/div[1]")).getText();
            driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[2]/a[1]/div[1]")).click();
            String filter_text = driver.findElement(By.xpath("//div[@id=\"drs-selection\"]/a[1]")).getText();
            assertEquals(filter_text, facet_name + " > " + facet_text);
        }
        catch(Exception e){
            System.out.println("More Creators filtering is not working.Error : " +e);
        }
    }
    
    @Test
    public void testCreatorUndoFiltering(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);     
            driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[2]/a[1]/div[1]")).click();
            driver.findElement(By.xpath("//div[@id=\"drs-selection\"]/a/span"));
            driver.findElement(By.xpath("//div[@id=\"drs-selection\"]/a/span")).click();
            String finder = driver.findElement(By.xpath("//div[@id=\"drs-selection\"]")).getText();
            assertEquals(finder, "");
        }
        catch(Exception e){
            System.out.println("More Creators unfiltering is not working.Error : " +e);
        }
    }
    
    
    @Test
    public void testCreationYearFacet(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            String element_text = driver.findElement(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[1]/b")).getText();
            System.out.println(driver.findElement(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[1]/b")).getText());
            assertEquals(element_text, "Creation year");
        }
        catch(Exception e){
            System.out.println("Creation Year facet not found. Error : " +e);
        }
    }
    
    @Test
    public void testCreationYearLinkPresent(){
        List<String> creator_years = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> creation_years_list  = driver.findElements(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[2]/a/div[1]"));
            for(WebElement year : creation_years_list){
                creator_years.add(year.getText());
            }
            assertThat(creator_years, JUnitMatchers.hasItem("1958"));
        }
        catch(Exception e){
            System.out.println("Creation year is not present.Error : " +e);
        }
    }
    
    @Test
    public void testCreationYearLinkNotPresent(){
        List<String> creator_years = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> creation_years_list  = driver.findElements(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[2]/a/div[1]"));
            for(WebElement year : creation_years_list){
                creator_years.add(year.getText());
            }
            assertThat(creator_years, not(JUnitMatchers.hasItem("20000")));
        }
        catch(Exception e){
            System.out.println("Creation year is falsely present.Error : " +e);
        }
    }
    
    @Test
    public void testCreationYearMoreButton(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            String element_text = driver.findElement(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[2]/button")).getText();
            System.out.println(driver.findElement(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[2]/button")).getText());
            assertEquals(element_text, "More Creation years");
        }
        catch(Exception e){
            System.out.println("More Creation Years button is not present.Error : " +e);
        }
    }
    
    @Test
    public void testCreationYearMoreButtonWorking(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            driver.findElement(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[2]/button")).click();
            WebElement more_creation_years_model = driver.findElement(By.xpath("//div[@id=\"drs_modal_creation_year_sim\"]"));
            assertTrue(more_creation_years_model.isEnabled());
        }
        catch(Exception e){
            System.out.println("More Creation Years button is not working.Error : " +e);
        }
    }
    
        @Test
    public void testCreationYearFiltering(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            String facet_name = driver.findElement(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[1]/b")).getText();
            String facet_text = driver.findElement(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[2]/a[1]/div[1]")).getText();
            driver.findElement(By.xpath("//div[@id=\"drs_creator_sim\"]/div/div[2]/a[1]/div[1]")).click();
            String filter_text = driver.findElement(By.xpath("//div[@id=\"drs-selection\"]/a[1]")).getText();
            assertEquals(filter_text, facet_name.toLowerCase() + "_sim > " + facet_text);
        }
        catch(Exception e){
            System.out.println("Creation Year filtering is not working.Error : " +e);
        }
    }
    
    @Test
    public void testCreationYearUndoFiltering(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);     
            driver.findElement(By.xpath("//div[@id=\"drs_creation_year_sim\"]/div/div[2]/a[1]/div[1]")).click();
            driver.findElement(By.xpath("//div[@id=\"drs-selection\"]/a/span"));
            driver.findElement(By.xpath("//div[@id=\"drs-selection\"]/a/span")).click();
            String finder = driver.findElement(By.xpath("//div[@id=\"drs-selection\"]")).getText();
            assertEquals(finder, "");
        }
        catch(Exception e){
            System.out.println("Creation Year unfiltering is not working.Error : " +e);
        }
    }
    
    @Test
    public void testSubjectFacet(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
//            Thread.sleep(14);
            String element_text = driver.findElement(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[1]/b")).getText();
            System.out.println(driver.findElement(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[1]/b")).getText());
            assertEquals(element_text, "Subject");
        }
        catch(Exception e){
            System.out.println("Subject facet not found. Error : " +e);
            fail();
        }
    }
    
    @Test
    public void testSubjectLinkPresent(){
        List<String> subject_names = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> subjects_list  = driver.findElements(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[2]/a/div[1]"));
            for(WebElement subject : subjects_list){
                subject_names.add(subject.getText());
            }
            assertThat(subject_names, JUnitMatchers.hasItem("Youth"));
        }
        catch(Exception e){
            System.out.println("Subject is not present.Error : " +e);
        }
    }
    
    @Test
    public void testSubjectLinkNotPresent(){
        List<String> subject_names = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> subjects_list  = driver.findElements(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[2]/a/div[1]"));
            for(WebElement subject : subjects_list){
                subject_names.add(subject.getText());
            }
            assertThat(subject_names, not(JUnitMatchers.hasItem("abcdef")));
        }
        catch(Exception e){
            System.out.println("Subject is falsely present.Error : " +e);
        }
    }
    
    @Test
    public void testSubjectMoreButton(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            String element_text = driver.findElement(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[2]/button")).getText();
            System.out.println(driver.findElement(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[2]/button")).getText());
            assertEquals(element_text, "More Subjects");
        }
        catch(Exception e){
            System.out.println("More Subjects button is not present.Error : " +e);
        }
    }

    @Test
    public void testSubjectMoreButtonWorking(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            driver.findElement(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[2]/button")).click();
            WebElement more_subject_model = driver.findElement(By.xpath("//div[@id=\"drs_modal_subject_sim\"]"));
            assertTrue(more_subject_model.isEnabled());
        }
        catch(Exception e){
            System.out.println("More Subjects button is not working.Error : " +e);
        }
    }
    
    @Test
    public void testSubjectFiltering(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            String facet_name = driver.findElement(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[1]/b")).getText();
            String facet_text = driver.findElement(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[2]/a[1]/div[1]")).getText();
            driver.findElement(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[2]/a[1]/div[1]")).click();
            String filter_text = driver.findElement(By.xpath("//div[@id=\"drs-selection\"]/a[1]")).getText();
            assertEquals(filter_text, facet_name + " > " + facet_text);
        }
        catch(Exception e){
            System.out.println("Subject filtering is not working.Error : " +e);
        }
    }
    
    @Test
    public void testSubjectUndoFiltering(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);     
            driver.findElement(By.xpath("//div[@id=\"drs_subject_sim\"]/div/div[2]/a[1]/div[1]")).click();
            driver.findElement(By.xpath("//div[@id=\"drs-selection\"]/a/span"));
            driver.findElement(By.xpath("//div[@id=\"drs-selection\"]/a/span")).click();
            String finder = driver.findElement(By.xpath("//div[@id=\"drs-selection\"]")).getText();
            assertEquals(finder, "");
        }
        catch(Exception e){
            System.out.println("Subject unfiltering is not working.Error : " +e);
        }
    }
    @Test
    public void testDepartmentFacet(){
            driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
//            Thread.sleep(14);
            String element_text = driver.findElement(By.xpath("//div[@id=\"drs_drs_department_ssim\"]/div/div[1]/b")).getText();
            System.out.println(driver.findElement(By.xpath("//div[@id=\"drs_drs_department_ssim\"]/div/div[1]/b")).getText());
            assertEquals(element_text, "Department");
        }
        catch(Exception e){
            System.out.println("Department facet not found. Error : " +e);
            fail();
        }
    }
    
    @Test
    public void testDepartmentLinkPresent(){
        List<String> dept_names = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> dept_list  = driver.findElements(By.xpath("//div[@id=\"drs_drs_department_ssim\"]/div/div[2]/a/div[1]"));
            for(WebElement dept : dept_list){
                dept_names.add(dept.getText());
            }
            assertThat(dept_names, JUnitMatchers.hasItem("School of Education"));
        }
        catch(Exception e){
            System.out.println("Department is not present.Error : " +e);
        }
    }
    
    @Test
    public void testDepartmentLinkNotPresent(){
        List<String> dept_names = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> dept_list  = driver.findElements(By.xpath("//div[@id=\"drs_drs_department_ssim\"]/div/div[2]/a/div[1]"));
            for(WebElement dept : dept_list){
                dept_names.add(dept.getText());
            }
            assertThat(dept_names, not(JUnitMatchers.hasItem("abcdef")));
        }
        catch(Exception e){
            System.out.println("Department falsely is present.Error : " +e);
        }
    }
    
    @Test
    public void testDegreeFacet(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
//            Thread.sleep(14);
            String element_text = driver.findElement(By.xpath("//div[@id=\"drs_drs_degree_ssim\"]/div/div[1]/b")).getText();
            System.out.println(driver.findElement(By.xpath("//div[@id=\"drs_drs_degree_ssim\"]/div/div[1]/b")).getText());
            assertEquals(element_text, "Course Degree");
        }
        catch(Exception e){
            System.out.println("Course Degree facet not found. Error : " +e);
            fail();
        }
    }
    
    @Test
    public void testDegreeLinkPresent(){
        List<String> degree_names = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> degree_list  = driver.findElements(By.xpath("//div[@id=\"drs_drs_degree_ssim\"]/div/div[2]/a/div[1]"));
            for(WebElement degree : degree_list){
                degree_names.add(degree.getText());
            }
            assertThat(degree_names, JUnitMatchers.hasItem("Ed.D."));
        }
        catch(Exception e){
            System.out.println("Degree is not present.Error : " +e);
        }
    }
    
    @Test
    public void testDegreeLinkNotPresent(){
        List<String> degree_names = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> degree_list  = driver.findElements(By.xpath("//div[@id=\"drs_drs_degree_ssim\"]/div/div[2]/a/div[1]"));
            for(WebElement degree : degree_list){
                degree_names.add(degree.getText());
            }
            assertThat(degree_names, not(JUnitMatchers.hasItem("abcdef")));
        }
        catch(Exception e){
            System.out.println("Degree is falsely present.Error : " +e);
        }
    } 
    
    @Test
    public void testTitleFacet(){
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
//            Thread.sleep(14);
            String element_text = driver.findElement(By.xpath("//div[@id=\"drs_drs_course_title_ssim\"]/div/div[1]/b")).getText();
            System.out.println(driver.findElement(By.xpath("//div[@id=\"drs_drs_course_title_ssim\"]/div/div[1]/b")).getText());
            assertEquals(element_text, "Course Title");
        }
        catch(Exception e){
            System.out.println("Course Title facet not found. Error : " +e);
            fail();
        }
    }
    
    @Test
    public void testTitleLinkPresent(){
        List<String> title_names = new ArrayList<>();
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> title_list  = driver.findElements(By.xpath("//div[@id=\"drs_drs_course_title_ssim\"]/div/div[2]/a/div[1]"));
            for(WebElement title : title_list){
                title_names.add(title.getText());
            }
            assertThat(title_names, JUnitMatchers.hasItem("English"));
        }
        catch(Exception e){
            System.out.println("Title is not present.Error : " +e);
        }
    }
    
    @Test
    public void testTitleLinkNotPresent(){
//        System.setProperty("webdriver.chrome.driver", "C:\\Users\\Kartik\\Downloads\\chromedriver.exe");
        List<String> title_names = new ArrayList<>();
        HtmlUnitDriver driver = new HtmlUnitDriver(BrowserVersion.BEST_SUPPORTED);
        driver.setJavascriptEnabled(true);
        try{
            driver.get(page_url);
            Thread.sleep(14);
            List<WebElement> title_list  = driver.findElements(By.xpath("//div[@id=\"drs_drs_course_title_ssim\"]/div/div[2]/a/div[1]"));
            for(WebElement title : title_list){
                title_names.add(title.getText());
            }
            assertThat(title_names, not(JUnitMatchers.hasItem("abcdef")));
        }
        catch(Exception e){
            System.out.println("Title is falsely present.Error : " +e);
        }
    } 
}
