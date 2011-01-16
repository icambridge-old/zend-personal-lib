<?php
require_once '../../library/Iain/Optimize/Concat.php';

/**
 * Iain_Optimize_Concat test case.
 */
class Iain_Optimize_ConcatTest extends PHPUnit_Framework_TestCase
{
       
    /**
     * Constructs the test case.
     */
    public function __construct ()
    {
        // TODO Auto-generated constructor
    }
    /**
     * Tests Iain_Optimize_Concat::addScript()
     */
    public function testAddScriptStringControllerStringScript ()
    {    	
    	Iain_Optimize_Concat::$rawFiles = array( 'scripts' => array(), 'styles' => array() );
        Iain_Optimize_Concat::addFile('scripts','index','index.js');
        $this->assertEquals(array('index') ,array_keys(Iain_Optimize_Concat::$rawFiles['scripts']));
        $this->assertEquals(array('index.js'), Iain_Optimize_Concat::$rawFiles['scripts']['index'] );
    }
    
    public function testAddScriptArrayControllerStringScript (){
    	
    	Iain_Optimize_Concat::$rawFiles = array( 'scripts' => array(), 'styles' => array() );
        Iain_Optimize_Concat::addFile('scripts',array('about','index'),'aboutindex.js');
        $this->assertEquals(array('about','index') ,array_keys(Iain_Optimize_Concat::$rawFiles['scripts']));
        $this->assertEquals(array('aboutindex.js'),  Iain_Optimize_Concat::$rawFiles['scripts']['index'] );
        $this->assertEquals(array('aboutindex.js'), Iain_Optimize_Concat::$rawFiles['scripts']['about'] );
        
    }
    
    public function testAddScriptStringControllerArrayScript() {
    	
    	Iain_Optimize_Concat::$rawFiles = array( 'scripts' => array(), 'styles' => array() );
        Iain_Optimize_Concat::addFile('scripts','index',array('indexone.js','indextwo.js'));
        $this->assertEquals(array('index') ,array_keys(Iain_Optimize_Concat::$rawFiles['scripts']));
        $this->assertEquals(array('indexone.js','indextwo.js'), Iain_Optimize_Concat::$rawFiles['scripts']['index'] );
    	
    }
    
    public function testAddScriptArrayControllerArrayScript() {
    	
    	Iain_Optimize_Concat::$rawFiles = array( 'scripts' => array(), 'styles' => array() );
        Iain_Optimize_Concat::addFile('scripts',array('about','index'),array('aboutindexone.js','aboutindextwo.js'));
        $this->assertEquals(array('about','index') ,array_keys(Iain_Optimize_Concat::$rawFiles['scripts']));
        $this->assertEquals(array('aboutindexone.js','aboutindextwo.js'), Iain_Optimize_Concat::$rawFiles['scripts']['index'] );
        $this->assertEquals(array('aboutindexone.js','aboutindextwo.js'), Iain_Optimize_Concat::$rawFiles['scripts']['about'] );
        
    }
    
    public function testBuildCombosTwoControllers(){
    	
    	Iain_Optimize_Concat::$rawFiles = array( 'scripts' => array(), 'styles' => array() );
    	
    	Iain_Optimize_Concat::addFile('scripts','index', array('one.js','two.js'));
    	Iain_Optimize_Concat::addFile('scripts','about', array('two.js','three.js'));
    	
    	$builds = Iain_Optimize_Concat::getScripts();
    	
    	$keys = array_keys($builds);
    	
    	// Test that we have the proper combos.
    	$this->assertContains('index',$keys);
    	$this->assertContains('about',$keys);
    	$this->assertContains('aboutindex',$keys);
    	// 
    	$this->assertEquals(array('one.js'),$builds['index']);
    	$this->assertEquals(array('two.js'),$builds['aboutindex']);
    	$this->assertEquals(array('three.js'),$builds['about']);
    	
    }
    
    
    public function testBuildCombosThreeControllers(){
    	
    	
    	Iain_Optimize_Concat::$rawFiles = array( 'scripts' => array(), 'styles' => array() );
    	
    	Iain_Optimize_Concat::addFile('scripts','index', array('index.js','aboutindex.js','aboutindexperson.js','indexperson.js'));
    	Iain_Optimize_Concat::addFile('scripts','about', array('about.js','aboutindex.js','aboutindexperson.js','aboutperson.js'));
    	Iain_Optimize_Concat::addFile('scripts','person', array('person.js','aboutperson.js','indexperson.js','aboutindexperson.js'));
    	
    	$builds = Iain_Optimize_Concat::getScripts();
    	
    	$keys = array_keys($builds);
    	
    	$this->assertContains('index',$keys);
    	$this->assertContains('about',$keys);
    	$this->assertContains('person',$keys);
    	$this->assertContains('aboutindex',$keys);
    	$this->assertContains('aboutperson',$keys);
    	$this->assertContains('indexperson',$keys);
    	$this->assertContains('aboutindexperson',$keys);
    	
    	$this->assertEquals(array('index.js'),$builds['index']);
    	$this->assertEquals(array('about.js'),$builds['about']);
    	$this->assertEquals(array('person.js'),$builds['person']);
    	$this->assertEquals(array('aboutindex.js'),$builds['aboutindex']);
    	$this->assertEquals(array('aboutperson.js'),$builds['aboutperson']);
    	$this->assertEquals(array('indexperson.js'),$builds['indexperson']);
    	$this->assertEquals(array('aboutindexperson.js'),$builds['aboutindexperson']);
    	
    }
    
    
    public function testBuildCombosFourControllers(){
    	
    	Iain_Optimize_Concat::$rawFiles = array( 'scripts' => array(), 'styles' => array() );
    	
 		Iain_Optimize_Concat::addFile('scripts','about','about.js');		
 		Iain_Optimize_Concat::addFile('scripts',array('about','index'),'aboutindex.js');
 		Iain_Optimize_Concat::addFile('scripts',array('about','index','person'),'aboutindexperson.js');
 		Iain_Optimize_Concat::addFile('scripts',array('about','index','picture'),'aboutindexpicture.js');
 		Iain_Optimize_Concat::addFile('scripts',array('about','index','person','picture'),'aboutindexpersonpicture.js');
 		Iain_Optimize_Concat::addFile('scripts',array('about','person'),'aboutperson.js');
 		Iain_Optimize_Concat::addFile('scripts',array('about','person','picture'),'aboutpersonpicture.js');
 		Iain_Optimize_Concat::addFile('scripts',array('about','picture'),'aboutpicture.js');
    	Iain_Optimize_Concat::addFile('scripts','index','index.js');
 		Iain_Optimize_Concat::addFile('scripts',array('index','person'),'indexperson.js');
 		Iain_Optimize_Concat::addFile('scripts',array('index','person','picture'),'indexpersonpicture.js');
 		Iain_Optimize_Concat::addFile('scripts',array('index','picture'),'indexpicture.js');
 		Iain_Optimize_Concat::addFile('scripts','person','person.js');
 		Iain_Optimize_Concat::addFile('scripts',array('person','picture'),'personpicture.js');
 		Iain_Optimize_Concat::addFile('scripts','picture','picture.js'); 
		
 		
 		$expectedBuilds = array("about","aboutindex","aboutindexperson","aboutindexpersonpicture","aboutindexpicture",
 								"aboutperson","aboutpersonpicture","aboutpicture","index","indexperson",
 								"indexpersonpicture","indexpicture","person","personpicture","picture");
 		
    	$builds = Iain_Optimize_Concat::getScripts();
    	
    	
    	$keys = array_keys($builds);
    	sort($keys);
    	
    	$this->assertEquals($expectedBuilds,$keys,"Builds aren't what was expected.");
    	
		foreach( $expectedBuilds as $buildName ){			
    		$this->assertEquals(array($buildName.'.js'),$builds[$buildName], 'Build '.$buildName.'.js is missing');
		}
		
    }
    
}

