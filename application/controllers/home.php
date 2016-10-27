<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->model("user_model");
	   $this->lang->load('basic', $this->config->item('language'));
		if($this->db->database ==''){
		redirect('install');	
		}
		
		
	 }

    private function set_upload_options()
    {
        //upload an image options
        $config = array();
        $config['upload_path'] = './files_upload/';
        $config['allowed_types'] = 'ppt|pptx|doc|docx|pdf';
        $config['max_size']      = '20000';
        $config['overwrite']     = FALSE;

        return $config;
    }
	 
	 public function index()
     {
         $courses=$this->db->get('general_courses');
         $streams=$this->db->get('streams');

         $data['streams']=$streams->result_array();
         $data['courses']=$courses->result_array();
         $this->load->view('header',$data);
         $this->load->view('home',$data);
         $this->load->view('footer',$data);
		 
	 }

	 public function viewstream($stream)
     {
         $this->db->start_cache();
         $this->db->where('general_courses.stream',$stream);
         $courses = $this->db->get('general_courses');
         $this->db->stop_cache();

         $this->db->start_cache();
         $streams=$this->db->query('select stream_name from streams');
         $this->db->stop_cache();


         $data['courses'] = $courses->result_array();
         $data['streams'] = $streams->result_array();
         $this->load->view('header',$data);
         $this->load->view('viewstream',$data);
         $this->load->view('footer',$data);


     }

    public function viewcourse($stream,$name)
    {
        $this->db->start_cache();
        $this->db->where('general_courses.stream',$stream);
        $this->db->where('general_courses.name',$name);
        $course = $this->db->get('general_courses');
        $course = $course->result_array();
        $this->db->stop_cache();

        $this->db->start_cache();
        $this->db->where('general_file.course_id',$course[0]['id']);
        $files = $this->db->get('general_file');
        $this->db->stop_cache();

        $this->db->flush_cache();

        $streams=$this->db->get('streams');

        $data['course'] = $course;
        $data['files'] = $files->result_array();
        $data['streams'] = $streams->result_array();
        $this->load->view('header',$data);
        $this->load->view('viewcourse',$data);
        $this->load->view('footer',$data);


    }

    public function addgeneralcoursepage()
    {

        $streams=$this->db->get('streams');

        $data['streams'] = $streams->result_array();
        $this->load->view('header',$data);
        $this->load->view('addgeneralcourse',$data);
        $this->load->view('footer',$data);

    }


    /**
     * @return string
     */
    public function addgeneralcourse()
    {

        $name = $this->input->post('course_name');
        $name = preg_replace("/\s+/", "_", $name);
        $department = $this->input->post('department');
        $short_description = $this->input->post('short_description');
        $long_description = $this->input->post('long_description');
        $content = $this->input->post('content');

        $patterns = array();
        $patterns[0]='<script>';

        $replace = array();
        $replace[0] = ' ';

        $content = preg_replace($patterns, $replace, $content);

        $this->db->start_cache();
        $this->db->where('general_courses.name',$name);
        $namecheck = $this->db->get('general_courses');
        $namecheckarray = $namecheck->result_array();
        $this->db->stop_cache();


        if($namecheckarray)
        {
            return $this->output->set_content_type('application/json')->set_status_header(500)->set_output(json_encode(array('success' => false, 'message' => "name_exists")));
        }
        else
        {
            $files_count = count($_FILES['file']['name']);

            $data = array(
                'name' => $name,
                'stream' => $stream,
                'short_description' => $short_description,
                'long_description' => $long_description,
                'content' => $content,
                'files' => $files_count,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            );

            $this->db->flush_cache();
            $this->db->insert('general_courses',$data);
            return $this->output->set_content_type('application/json')->set_status_header(500)->set_output(json_encode($this));

            $course_id = $this->db->insert_id();
            $uploaded = 0;

            if(!empty($_FILES['file']['name']))
            {
                $files_count = count($_FILES['file']['name']);

                for($i=0; $i<$files_count; $i++)
                {
                    $_FILES['file']['file_ext']= $_FILES['file']['file_ext'][$i];
                    $_FILES['file']['type'] = $_FILES['file']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['file']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['file']['size'][$i];

                    $uploadPath = 'uploads/files/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'ppt|pptx|docx|doc|pdf';

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('file'))
                    {
                        $uploaded++;
                    }
                    else
                    {
                        return $this->output->set_content_type('application/json')->set_status_header(500)->set_output(json_encode(array('success' => false, 'message' => 'invalid_file')));
                    }
                }

                for($i=0; $i<$files_count; $i++)
                {
                    $original_name = $_Files['file']['name'][$i];
                    $type = $_Files['file']['file_ext'][$i];
                    $file_name = $name."_".($i+1);
                    $location = $department."/".$file_name."/".$_Files['file']['name'][$i];;
                    if($type == ".pdf")
                    {
                        $temp_location = "http://docs.google.com/gview?url=http://www.uky.edu/~achan2/pics/beatles/Beatles-Something.pdf?dl=0&embedded=true";
                    }
                    elseif ($type == ".ppt" || $type == ".pptx")
                    {
                        $temp_location = "http://docs.google.com/gview?url=www.istartedsomething.com/uploads/toolkit_0_2.pptx?dl=0&embedded=true";
                    }
                    elseif ($type == ".doc" || $type == ".docx")
                    {
                        $temp_location = "http://docs.google.com/gview?url=calibre-ebook.com/downloads/demos/demo.docx?dl=0&embedded=true";
                    }

                    $filedata = array(
                        'original_name' => $original_name,
                        'type' => $type,
                        'name' => $name,
                        'course_id' => $course_id,
                        'location' => $location,
                        'temp_location' => $temp_location
                    );

                    $this->db->insert('general_file',$filedata);
                }

                if($files_count == $uploaded)
                {
                    return $this->output->set_content_type('application/json')->set_status_header(500)->set_output(json_encode(array('success' => true, 'message' => 'success')));
                }
                else
                {
                    return $this->output->set_content_type('application/json')->set_status_header(500)->set_output(json_encode(array('success' => false, 'message' => 'not_all_files')));
                }
            }
            return $this->output->set_content_type('application/json')->set_status_header(500)->set_output(json_encode(array('success' => true, 'message' => 'success')));

        }



    }



	
	
}
