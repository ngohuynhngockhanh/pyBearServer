<?php
namespace Controllers;

use Core\View;
use Core\Controller;
use Models\StoryModel;

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
    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
		$this->storyModel = new StoryModel();
    }

    /**
     * List all story from variable $_GET
     */
    public function getList()
    {
		$data = $this->storyModel->getList();
        View::render('story/list', $data);
    }

}
