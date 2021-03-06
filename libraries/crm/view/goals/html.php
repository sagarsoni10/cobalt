<?php
/*------------------------------------------------------------------------
# Cobalt
# ------------------------------------------------------------------------
# @author Cobalt
# @copyright Copyright (C) 2012 cobaltcrm.org All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.cobaltcrm.org
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

class CobaltViewGoalsHtml extends JViewHtml
{
	function render()
	{
        $app = JFactory::getApplication();

        //determine the type of goal we are creating//editing
        $type = $app->input->get('type');

	    //edit layout
	    if ( $this->getLayout() == 'edit' ){
	        
            switch($type){
                case "win_cash":
                    $header = ucwords(CRMText::_('COBALT_WIN_MORE_CASH'));
                    break;
                case "win_deals":
                    $header = ucwords(CRMText::_('COBALT_WIN_MORE_DEALS'));
                    break;
                case "move_deals";
                    $header = ucwords(CRMText::_('COBALT_MOVE_DEALS_FORWARD'));
                    break;
                case "complete_tasks";
                    $header = ucwords(CRMText::_('COBALT_COMPLETE_TASKS'));
                    break;
                case "write_notes";
                    $header = ucwords(CRMText::_('COBALT_WRITE_NOTES'));
                    break;
                case "create_deals";
                    $header = ucwords(CRMText::_('COBALT_CREATE_DEALS'));
                    break;
                default:
                   $app->redirect('index.php?view=goals');
                    break;   
            }
    
            $this->header = $header;

	    } else if ( $this->getLayout() != 'add' ) {
	        
            //load model
            $model = new CobaltModelGoal();
            
            //get all goals from model depending on user type
            $member_role = CobaltHelperUsers::getRole();
            
            //basic members
            if ( $member_role == 'basic' ){
                $individual_goals = $model->getIndividualGoals();
                $team_goals = $model->getTeamGoals();
                $company_goals = $model->getCompanyGoals();
                $leaderboards = $model->getLeaderBoards();
            }
            
            //managers
            if ( $member_role == 'manager' ){
                // $individual_goals = $model->getManagerIndividualGoals();
                $individual_goals = $model->getIndividualGoals();
                $team_goals = $model->getTeamGoals();
                $company_goals = $model->getCompanyGoals();
                $leaderboards = $model->getLeaderBoards();
            }
        
            //executives
            if ( $member_role == 'exec' ){
                // $individual_goals = $model->getExecIndividualGoals();
                $individual_goals = $model->getIndividualGoals();
                // $team_goals = $model->getExecTeamGoals();
                $team_goals = $model->getTeamGoals();
                $company_goals = $model->getCompanyGoals();
                $leaderboards = $model->getLeaderBoards();
            }
            
            //assign goals to global goal object to pass through to view   
            $goals = new stdClass();
            $goals->individual_goals = $individual_goals;
            $goals->team_goals = $team_goals;
            $goals->company_goals = $company_goals;
            $goals->leaderboards = $leaderboards;
            
            //if we get results then load the default goals page else show the add goals page
            $goal_count = false;
            foreach ( $goals as $goal_list ){
                if ( count($goal_list) > 0 ){
                    $goal_count = true;
                }
            }
            if ( $goal_count ){
                //set layout    
                $this->setLayout('default');
                //assign view refs
                $this->goals = $goals;
            }else{
                //add goal layout
                $this->setLayout('add');
            }
	    }

        //load java libs
        $doc = JFactory::getDocument();
        $doc->addScript( JURI::base().'libraries/crm/media/js/goal_manager.js' );

        //get associated members and teams
        $teams = CobaltHelperUsers::getTeams();
        $users = CobaltHelperUsers::getUsers();
        $member_role = CobaltHelperUsers::getRole();
        $user_id = CobaltHelperUsers::getUserId();
        $team_id = CobaltHelperUsers::getTeamId();
        
        //assign view refs
        $this->type = $type;
        $this->teams = $teams;
        $this->users = $users;
        $this->user_id = $user_id;
        $this->team_id = $team_id;
        $this->member_role = $member_role;
        $this->leaderboard_list = CobaltHelperDropdown::getLeaderBoards();

	    //display
		return parent::render();
	}
	
}