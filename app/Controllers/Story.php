<?php
namespace Controllers;

use Core\View;
use Core\Controller;
use Models\StoryModel;
use Models\RestAPI;

/*
 * Story controller
 *
 * @author Ngoc Khanh Ngo Huynh - nhnkhanh@arduino.vn - http://arduino.vn/ksp
 * @version 1.0
 * @date March 07, 2016
 * @date updated March 07, 2016
 */
class Story extends Controller
{
	private $storyModel;
	private $RestAPI;
    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
		$this->storyModel = new StoryModel();
		$this->RestAPI = new RestAPI();
    }

    /**
     * List all story from variable $_GET
     */
    public function getList()
    {
		$data = $this->storyModel->getList();
        View::render('restAPI', $this->RestAPI->output($data));
    }

}
