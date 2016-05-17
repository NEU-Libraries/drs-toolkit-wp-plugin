<?php
/**
 * Class SampleTest
 *
 * @package
 */

 require_once 'vendor/autoload.php';
 // require_once 'PHPUnit/Extensions/Selenium2TestCase.php';


class TestTest extends PHPUnit_Extensions_Selenium2TestCase {
	/**
	 * A single example test.
	 */

	 protected function setUp(){
		 $this->setBrowser('firefox');
     $this->setPort(4441);
		 $this->setBrowserUrl('http://localhost');
		 $this->setSeleniumServerRequestsTimeout(999999999);
	 }

	 protected function wp_login(){
		 $this->url('http://liblab.neu.edu/drstest/wp-login.php');
		 $this->byId( 'user_login' )->value( 'testuser' );
		 $this->byId('user_pass')->value('P@$$W0rd');
		 $this->byId('wp-submit')->submit();
		 $this->timeouts()->implicitWait(999999999);
	 }

	 protected function wp_add_page(){
		 $this->wp_login();
		 $this->url('http://liblab.neu.edu/drstest/wp-admin/post-new.php?post_type=page');
		 $this->byId('insert-drs')->click();
	 }

	 public function test_get_index(){
		 $this->wp_add_page();
		 $this->byId('ui-id-6')->click();
		 $this->byCssSelector("#sortable-timeline-list li:nth-of-type(20)")->click();
		 $imgs = $this->elements($this->using('css selector')->value('#sortable-timeline-list li'));
		 $this->assertEquals(20, count($imgs));
	 }

	 public function test_search_index(){
		 $this->wp_add_page();
		 $this->byId('ui-id-6')->click();
		 $this->byCssSelector("#sortable-timeline-list li:nth-of-type(20)")->click();
		 $this->byId('search-timeline')->value("house");
		 $this->byId('search-button-timeline')->click();
		 $this->byCssSelector("#sortable-timeline-list li:nth-of-type(20)")->click();
		 $imgs = $this->elements($this->using('css selector')->value('#sortable-timeline-list li'));
		 $this->assertEquals(20, count($imgs));
	 }

	 public function test_insert_one_shortcode(){
		 $this->wp_add_page();
		 $this->byId('ui-id-6')->click();
		 $this->byCssSelector("#sortable-timeline-list li:nth-of-type(20)")->click();
		 $imgs = $this->elements($this->using('css selector')->value('#sortable-timeline-list li img'));
		 $this->assertEquals(20, count($imgs));
		 $elem = $this->byCssSelector("#sortable-timeline-list #drstile-0");
		 $pid = $elem->value();
		 $elem->click();
		 $this->byId('drstk_insert_timeline')->click();
		 $this->byId('content-html')->click();
		 $this->assertContains($pid, $this->byCssSelector("#wp-content-editor-container textarea")->value());
	 }

	 public function test_insert_multi_shortcode(){
		 $this->wp_add_page();
		 $this->byId('ui-id-6')->click();
		 $this->byCssSelector("#sortable-timeline-list li:nth-of-type(20)")->click();
		 $imgs = $this->elements($this->using('css selector')->value('#sortable-timeline-list li img'));
		 $this->assertEquals(20, count($imgs));
		 $elem1 = $this->byCssSelector("#sortable-timeline-list #drstile-0");
		 $pid1 = $elem1->value();
		 $elem1->click();
		 $elem2 = $this->byCssSelector("#sortable-timeline-list #drstile-1");
		 $pid2 = $elem2->value();
		 $elem2->click();
		 $this->byId('drstk_insert_timeline')->click();
		 $this->byId('content-html')->click();
		 $this->assertContains($pid1, $this->byCssSelector("#wp-content-editor-container textarea")->value());
		 $this->assertContains($pid2, $this->byCssSelector("#wp-content-editor-container textarea")->value());
	 }

	//  public function test_url(){
	 //
	// 	 $this->url('http://liblab.neu.edu/drstest/timeline-test-sprint-4');
	// 	 $elements = $this->elements($this->using('css selector')->value('#_1949-1950-roxbury-clubhouse-basketball-team-posing-with-their-trophy'));
	// 	 $this->assertEquals(1, count($elements));
	 //
	//  }

}
