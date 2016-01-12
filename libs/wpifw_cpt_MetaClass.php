<?php
/**
 * wpifw_cpt_MetaClass
 *
 * A Class that...
 *
 *
 *
 * @package    wpifw
 * @subpackage custom_meta
 * @author     Peter Gordon <hello@petergordon.net>
 * @version    0.0.1
 * @since      0.0.1
 */
class wpifw_cpt_MetaClass {
	
	/**
	 * Hook functions into the appropriate actions
	 * when the class is constructed.
	 */
	public function __construct() {
		
		add_action( 'add_meta_boxes', array( $this, 'wpifw_add_meta_box' ) );
		add_action( 'save_post', array( $this, 'wpifw_save' ) );
		
	}

	/**
	 * Adds the meta box container.
	 */
	public function wpifw_add_meta_box( $post_type ) {
		
		if ( $post_type == 'scene' ) {
			
			add_meta_box (
				'custom-meta'
				,__( 'Scene Actions', 'wpifw_text' )
				,array( $this, 'render_scene_meta_box_content' )
				,$post_type
				,'advanced'
				,'high'
			);
			
		} else if ( $post_type == 'characters' ) {
			
			add_meta_box (
				'custom-meta'
				,__( 'Character Options', 'wpifw_text' )
				,array( $this, 'render_character_meta_box_content' )
				,$post_type
				,'advanced'
				,'high'
			);
			
		} else if ( $post_type == 'character_defaults' ) {
			
			add_meta_box (
				'custom-meta'
				,__( 'Character Class Defaults', 'wpifw_text' )
				,array( $this, 'render_character_defaults_meta_box_content' )
				,$post_type
				,'advanced'
				,'high'
			);
			
		}
	}
	
	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function wpifw_save( $post_id ) {
		
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['wpifw_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['wpifw_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'wpifw_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}
		
		if ($_POST['post_type'] == 'scene') {

			// Safe to save the data now.
			$old = get_post_meta($post_id, '_follow_on_options', true);
			$newchoice = array();
			
			$selected = $_POST['selected'];
			
			if ($selected == 'no-options') {
				
				$ending = $_POST['ending'];
				$newchoice[1]['ending'] = stripslashes( strip_tags( $ending ) );
	
				
			} else if ($selected == 'choice-options') {
				
				$choice = $_POST['choice'];
				$destination = $_POST['destination'];
				$count = count( $choice );
				
				for ( $i = 0; $i < $count; $i++ ) {
					if ( $choice[$i] != '' ) {
						$newchoice[$i]['choice'] = stripslashes( strip_tags( $choice[$i] ) );
						$newchoice[$i]['destination'] = stripslashes( strip_tags( $destination[$i] ) );
					} 
				}
				
				
				
				
			} else if ($selected == 'fight-options') {
				
				$combatants = $_POST['combatants'];
				$flee = $_POST['flee'];
				$fleeDestination = $_POST['fleeDestination'];
				$successChance = $_POST['successChance'];
				$mercy = $_POST['mercy'];
				$mercyDestination = $_POST['mercyDestination'];
				$victory = $_POST['victory'];
				$defeat = $_POST['defeat'];
				$count = count( $combatants );
				
				$combatantsArray = array();
				
				for ( $i = 0; $i < $count; $i++ ) {
					if ( $combatants[$i] != '' ) {
						if ( $combatants[$i] != 'Select...' ) {
							if ( $combatants[$i] != 'Random...' ) {
							
								array_push( $combatantsArray, stripslashes( strip_tags( $combatants[$i] ) ));
								
							}
						}
					}
				}
				$newchoice[1]['combatants'] = $combatantsArray;
				$newchoice[2]['flee'] = $flee;
				$newchoice[2]['fleeDestination'] = $fleeDestination;
				$newchoice[2]['successChance'] = $successChance;
				$newchoice[2]['mercy'] = $mercy;
				$newchoice[2]['mercyDestination'] = $mercyDestination;
				$newchoice[2]['victory'] = $victory;
				$newchoice[2]['defeat'] = $defeat;
				
			} else if ($selected == 'luck-options') {
				
				$successChance = $_POST['successChance'];
				$success = $_POST['success'];
				$fail = $_POST['fail'];
				$decline = $_POST['decline'];

				$newchoice[1]['successChance'] = stripslashes( strip_tags( $successChance ) );
				$newchoice[1]['success'] = stripslashes( strip_tags( $success ) );
				$newchoice[1]['fail'] = stripslashes( strip_tags( $fail ) );
				$newchoice[1]['decline'] = stripslashes( strip_tags( $decline ) );
				
			} else if ($selected == 'passphrase-options') {
				
				$passphrase = $_POST['passphrase'];
				
				$correctDestination = $_POST['correctDestination'];
				$incorrectDestination = $_POST['incorrectDestination'];
				$declineDestination = $_POST['declineDestination'];
				
				$newchoice[1]['passphrase'] = stripslashes( strip_tags($passphrase ) );
				$newchoice[1]['correctDestination'] = stripslashes( strip_tags($correctDestination ) );
				$newchoice[1]['incorrectDestination'] = stripslashes( strip_tags($incorrectDestination ) );
				$newchoice[1]['declineDestination'] = stripslashes( strip_tags( $declineDestination ) );
				
			
			}
			
			// Insert selection into array[0] 
			array_unshift($newchoice, $selected);
	
			// Update the meta field.
			if ( !empty( $newchoice ) && $newchoice != $old ) {
				
				update_post_meta( $post_id, '_follow_on_options', $newchoice );
				
			} else if ( empty($newchoice) && $old ) {
				
				delete_post_meta( $post_id, '_follow_on_options', $old );
				
			}
			
		} else if ( $_POST['post_type'] == 'character_defaults' ) {
			
			$old = get_post_meta($post_id, '_character_defaults', true);
			$newchoice = array();
			
			$key = $_POST['post_title'];
				
			// Check for alignment defaults and insert into Array 
			$alignment = array();
			if ($_POST['wpifw_alignment_friend'] == 'Friend') {
				$friend = 'Friend';
				array_push($alignment, $friend);
			}
			if ($_POST['wpifw_alignment_foe'] == 'Foe') {
				$foe = 'Foe';
				array_push($alignment, $foe);
			}
			if ($_POST['wpifw_alignment_neutral'] == 'Neutral') {
				$neutral = 'Neutral';
				array_push($alignment, $neutral);
			}
			if ($alignment[0] != '') {
				$newchoice[$key]['alignment'] = $alignment;
			}
			$alignment = (object)$alignment;
			
			// Check for gender defaults and insert into Array 
			$gender = array();
			if ($_POST['wpifw_gender_male'] == 'Male') {
				$male = 'Male';
				array_push($gender, $male);
			}
			if ($_POST['wpifw_gender_female'] == 'Female') {
				$female = 'Female';
				array_push($gender, $female);
			}
			if ($_POST['wpifw_gender_it'] == 'It') {
				$it = 'It';
				array_push($gender, $it);
			}
			if ($gender[0] != '') {
				$newchoice[$key]['gender'] = $gender;
			}
			$gender = (object)$gender;
			
			// Check for behaviour defaults and insert into Array 
			$behaviour = array();
			if ($_POST['wpifw_behaviour'] == 'Attacker') {
				$attacker = 'Attacker';
				array_push( $behaviour, $attacker );
				
			} else if ($_POST['wpifw_behaviour'] == 'Defender') {
				$defender = 'Defender';
				array_push( $behaviour, $defender );
				
			} else if ($_POST['wpifw_behaviour'] == 'None') {
				$behaviour = '';
			}
			if ($behaviour[0] != '') {
				$newchoice[$key]['behaviour'] = $behaviour;
			}
			$behaviour = (object)$behaviour;
			
			// Check for skill defaults and insert into Array 
			$skilld1 = $_POST['wpifw_skill_d1'];
			$skilld2 = $_POST['wpifw_skill_d2'];
			if ($skilld1 != 2 || $skilld2 != 6) {
				$skill = array();
				$diceroll = $skilld1 . 'd' . $skilld2;
				array_push( $skill, $diceroll );
				$newchoice[$key]['skill'] = $skill;
			}
			
			// Check for stamina defaults and insert into Array 
			$staminad1 = $_POST['wpifw_stamina_d1'];
			$staminad2 = $_POST['wpifw_stamina_d2'];
			if ($staminad1 != 2 || $staminad2 != 6) {
				$stamina = array();
				$diceroll = $staminad1 . 'd' . $staminad2;
				array_push( $stamina, $diceroll );
				$newchoice[$key]['stamina'] = $stamina;
			}
			
			// Check for stamina defaults and insert into Array 
			$luckd1 = $_POST['wpifw_luck_d1'];
			$luckd2 = $_POST['wpifw_luck_d2'];
			if ($luckd1 != 2 || $luckd2 != 6) {
				$luck = array();
				$diceroll = $luckd1 . 'd' . $luckd2;
				array_push( $luck, $diceroll );
				$newchoice[$key]['luck'] = $luck;
			}
			
			// Check for gold defaults and insert into Array 
			if ( $_POST['wpifw_has_gold'] == 'has_gold' ) {
			
				$goldd1 = $_POST['wpifw_gold_d1'];
				$goldd2 = $_POST['wpifw_gold_d2'];
				$gold = array();
				$diceroll = $goldd1 . 'd' . $goldd2;
				array_push( $gold, $diceroll );
				//$gold = (object)$gold;
				$newchoice[$key]['gold'] = $gold;
				
			} else {
				$gold = array();
				array_push( $gold, 0 );
				//$gold = (object)$gold;
				$newchoice[$key]['gold'] = $gold;
			
			}

			// Update the meta field.
			if ( !empty( $newchoice ) && $newchoice != $old ) {
				
				update_post_meta( $post_id, '_character_defaults', $newchoice );
				
			} else if ( empty($newchoice) && $old ) {
				
				delete_post_meta( $post_id, '_character_defaults', $old );
				
			}
			
		} else if ( $_POST['post_type'] == 'characters' ) {
			
			$old = get_post_meta($post_id, '_character_profile', true);
			
			$newchoice = array();
			
			$key = $_POST['post_title'];
			
			$newchoice[$key]['name'] = stripslashes( $_POST['post_title'] );
			
			$newchoice[$key]['useClass'] = $_POST['wpifw_use_class_yes'];
			
			if ($_POST['wpifw_use_class_yes'] != 'on') {
				$newchoice[$key]['species'] = stripslashes( $_POST['wpifw_character_species'] );
			}
			$newchoice[$key]['alignment'] = stripslashes( $_POST['wpifw_character_alignment'] );
			$newchoice[$key]['gender'] = stripslashes( $_POST['wpifw_character_gender'] );
			$newchoice[$key]['behaviour'] = stripslashes( $_POST['wpifw_character_behaviour'] );
			
			$newchoice[$key]['useDefaults'] = $_POST['wpifw_use_defaults_yes'];

			
			
			if ($_POST['wpifw_use_defaults_yes'] != 'on') { 
			
				// Check for skill defaults and insert into Array 
				$skilld1 = $_POST['wpifw_skill_d1'];
				$skilld2 = $_POST['wpifw_skill_d2'];
				if ($skilld1 != 2 || $skilld2 != 6) {
					$skill = array();
					$diceroll = $skilld1 . 'd' . $skilld2;
					array_push( $skill, $diceroll );
					$newchoice[$key]['skill'] = $skill;
				}
				
				// Check for stamina defaults and insert into Array 
				$staminad1 = $_POST['wpifw_stamina_d1'];
				$staminad2 = $_POST['wpifw_stamina_d2'];
				if ($staminad1 != 2 || $staminad2 != 6) {
					$stamina = array();
					$diceroll = $staminad1 . 'd' . $staminad2;
					array_push( $stamina, $diceroll );
					$newchoice[$key]['stamina'] = $stamina;
				}
				
				// Check for stamina defaults and insert into Array 
				$luckd1 = $_POST['wpifw_luck_d1'];
				$luckd2 = $_POST['wpifw_luck_d2'];
				if ($luckd1 != 2 || $luckd2 != 6) {
					$luck = array();
					$diceroll = $luckd1 . 'd' . $luckd2;
					array_push( $luck, $diceroll );
					$newchoice[$key]['luck'] = $luck;
				}
				
				// Check for gold defaults and insert into Array 
				if ( $_POST['wpifw_has_gold'] == 'has_gold' ) {
				
					$goldd1 = $_POST['wpifw_gold_d1'];
					$goldd2 = $_POST['wpifw_gold_d2'];
					$gold = array();
					$diceroll = $goldd1 . 'd' . $goldd2;
					array_push( $gold, $diceroll );
					//$gold = (object)$gold;
					$newchoice[$key]['gold'] = $gold;
					
				} else {
					$gold = array();
					array_push( $gold, 0 );
					//$gold = (object)$gold;
					$newchoice[$key]['gold'] = $gold;
				
				}
			
			} else {
				
				//$newchoice[$key]['skill'] = '';
				//$newchoice[$key]['stamina'] = '';
				//$newchoice[$key]['luck'] = '';
				//$newchoice[$key]['gold'] = '';	
				
			}

			
			// Update the meta field.
			if ( !empty( $newchoice ) && $newchoice != $old ) {
				
				update_post_meta( $post_id, '_character_profile', $newchoice );
				
			} else if ( empty($newchoice) && $old ) {
				
				delete_post_meta( $post_id, '_character_profile', $old );
				
			}

			
		}

	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_character_defaults_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'wpifw_inner_custom_box', 'wpifw_inner_custom_box_nonce' );

		//Use get_post_meta to retrieve an existing value from the database.
		$show_the_value = get_post_meta( $post->ID, '_character_defaults', true );
		if (!$show_the_value) {
			$newCharacterClass =  1;
			$show_the_value = array();
		}
		$key = get_the_title( $ID );
		if ( !$key ) { $key = $ID; }

		// Display the form, using the current value.
		echo '<table class="form-table">';
			echo '<tbody>';

				echo '<tr scope="row">';
				if (!$newCharacterClass) {
					$alignmentCount = count($show_the_value[$key]['alignment']);
				}
					echo '<th>';
						echo '<label for="wpifw_alignment">';_e( 'Alignment', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
						echo '<input type="checkbox" id="wpifw_alignment_friend" name="wpifw_alignment_friend" value="Friend" ';
							if( $alignmentCount ) {
								for ( $i = 0; $i < $alignmentCount; $i++ ) {
									if ( $show_the_value[$key]['alignment'][$i] == 'Friend' ) { echo 'checked'; }
								}
							}
						echo ' />  '; _e( 'Friend', 'wpifw_text' );
						
						echo '<br /><br /><input type="checkbox" id="wpifw_alignment_foe" name="wpifw_alignment_foe" value="Foe" ';
							if( $alignmentCount ) {
								for ( $i = 0; $i < $alignmentCount; $i++ ) {	
									if ( $show_the_value[$key]['alignment'][$i] == 'Foe' ) { echo 'checked'; }
								}
							}
						echo ' />  '; _e( 'Foe', 'wpifw_text' ); 
						
						echo '<br /><br /><input type="checkbox" id="wpifw_alignment_neutral" name="wpifw_alignment_neutral" value="Neutral"';
							if( $alignmentCount ) {
								for ( $i = 0; $i < $alignmentCount; $i++ ) {	
									if ( $show_the_value[$key]['alignment'][$i] == 'Neutral' ) { echo 'checked'; }
								}
							}
						echo '/>  '; _e( 'Neutral', 'wpifw_text' ); 
					echo '</td>';
				echo '</tr>';
				
				echo '<tr scope="row">';
				if (!$newCharacterClass) {
					$genderCount = count($show_the_value[$key]['gender']);
				}
					echo '<th>';
						echo '<label for="wpifw_gender">';_e( 'Gender', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
						echo '<input type="checkbox" id="wpifw_gender_male" name="wpifw_gender_male" value="Male" ';
							if( $genderCount ) {
								for ( $i = 0; $i < $genderCount; $i++ ) {	
									if ( $show_the_value[$key]['gender'][$i] == 'Male' ) { echo 'checked'; }
								}
							}
						echo '/>  '; _e( 'Male', 'wpifw_text' ); 
						
						echo '<br /><br /><input type="checkbox" id="wpifw_gender_female" name="wpifw_gender_female" value="Female" ';
							if( $genderCount ) {							
								for ( $i = 0; $i < $genderCount; $i++ ) {	
									if ( $show_the_value[$key]['gender'][$i] == 'Female' ) { echo 'checked'; }
								}
							}
						echo '/>  '; _e( 'Female', 'wpifw_text' );
						
						echo '<br /><br /><input type="checkbox" id="wpifw_gender_it" name="wpifw_gender_it" value="It" ';
							if( $genderCount ) {
								for ( $i = 0; $i < $genderCount; $i++ ) {	
									if ( $show_the_value[$key]['gender'][$i] == 'It' ) { echo 'checked'; }
								}
							}
						echo '/>  '; _e( 'It', 'wpifw_text' ); 
					echo '</td>';
				echo '</tr>';
				
				echo '<tr scope="row">';
				if (!$newCharacterClass) {
					$behaviourType = $show_the_value[$key]['behaviour'][0]; 
				}
					echo '<th>';
						echo '<label for="wpifw_behaviour">';_e( 'Behaviour', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
						echo '<input type="radio" id="wpifw_behaviour" name="wpifw_behaviour" value="Attacker" ';
							if( $behaviourType ) {
								if ( $behaviourType == 'Attacker' ) { echo 'checked'; }
							}
						echo '/>  '; _e( 'Attacker', 'wpifw_text' ); 
						
						echo '<br /><br /><input type="radio" id="wpifw_behaviour" name="wpifw_behaviour" value="Defender" ';
							if( $behaviourType ) {
								if ( $behaviourType == 'Defender' ) { echo 'checked'; }
							}
						echo '/>  '; _e( 'Defender', 'wpifw_text' ); 
						
						echo '<br /><br /><input type="radio" id="wpifw_behaviour" name="wpifw_behaviour" value="None" ';
								if ( $behaviourType == '' || !$behaviourType ) { echo 'checked'; }
						echo '/>  '; _e( 'None', 'wpifw_text' ); 

					echo '</td>';
				echo '</tr>';

				echo '<tr scope="row">';
				if (!$newCharacterClass) { $skillroll = $show_the_value[$key]['skill'][0];
				} else { $skillroll = "2d6"; }
					echo '<th>';
						echo '<label for="wpifw_skill">';_e( 'Skill', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
						echo '<select type="dropdown" id="wpifw_skill_d1" name="wpifw_skill_d1">';
							echo wpifw_utils::wpifw_generate_dice_options( 'quantity', $skillroll );
						echo '</select>';
						echo ' x ';
						echo '<select type="dropdown" id="wpifw_skill_d2" name="wpifw_skill_d2">';
							echo wpifw_utils::wpifw_generate_dice_options( 'type', $skillroll );
						echo '</select>';
						echo ' Sided Dice ';
					echo '</td>';
				echo '</tr>';		
		
				echo '<tr scope="row">';
				if (!$newCharacterClass) { $staminaroll = $show_the_value[$key]['stamina'][0];
				} else { $staminaroll = "2d6"; }
					echo '<th>';
						echo '<label for="wpifw_stamina">';_e( 'Stamina', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
						echo '<select type="dropdown" id="wpifw_stamina_d1" name="wpifw_stamina_d1">';
							echo wpifw_utils::wpifw_generate_dice_options( 'quantity', $staminaroll );
						echo '</select>';
						echo ' x ';
						echo '<select type="dropdown" id="wpifw_stamina_d2" name="wpifw_stamina_d2">';
							echo wpifw_utils::wpifw_generate_dice_options( 'type', $staminaroll );
						echo '</select>';
						echo ' Sided Dice ';
					echo '</td>';
				echo '</tr>';	

				echo '<tr scope="row">';
				if (!$newCharacterClass) { $luckroll = $show_the_value[$key]['luck'][0];
				} else { $luckroll = "2d6"; }
					echo '<th>';
						echo '<label for="wpifw_luck">';_e( 'Luck', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
						echo '<select type="dropdown" id="wpifw_luck_d1" name="wpifw_luck_d1">';
							echo wpifw_utils::wpifw_generate_dice_options( 'quantity', $luckroll );
						echo '</select>';
						echo ' x ';
						echo '<select type="dropdown" id="wpifw_luck_d2" name="wpifw_luck_d2">';
							echo wpifw_utils::wpifw_generate_dice_options( 'type', $luckroll );
						echo '</select>';
						echo ' Sided Dice ';
					echo '</td>';
				echo '</tr>';	
				
				echo '<tr scope="row">';
				if (!$newCharacterClass) { $goldroll = $show_the_value[$key]['gold'][0];
				} else { $goldroll = "2d6"; }
					echo '<th>';
						echo '<label for="wpifw_gold">';_e( 'Currency', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
						echo '<input type="checkbox" id="wpifw_has_gold" name="wpifw_has_gold" value="has_gold" ';
							
							if ( $goldroll != 0 ) {	echo ' checked'; }
							
						echo '/> '; _e( 'Can Have Currency', 'wpifw_text' ); echo '<br />'; 
						echo '<div id="gold_selector" style="display:';
							
							if ( $goldroll != 0 ) {	echo 'block">'; } else { echo 'none">';	}
						
							echo '<br /><select type="dropdown" id="wpifw_gold_d1" name="wpifw_gold_d1">';
								echo wpifw_utils::wpifw_generate_dice_options( 'quantity', $goldroll );
							echo '</select>';
							echo ' x ';
							echo '<select type="dropdown" id="wpifw_gold_d2" name="wpifw_gold_d2">';
								echo wpifw_utils::wpifw_generate_dice_options( 'type', $goldroll );
							echo '</select>';
							echo ' Sided Dice ';
							
						echo '</div>';
					echo '</td>';
				echo '</tr>';	
		
			echo '</tbody>';		
		echo '</table>';
			
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_character_meta_box_content( $post ) {
	
		//n = name, sp = species, a = alignment, g = gender, b = behaviour, sk = skill, st = stamina, l = luck, gd = gold;
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'wpifw_inner_custom_box', 'wpifw_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$show_the_value = get_post_meta( $post->ID, '_character_profile', true );
		if (!$show_the_value) {
			$newCharacter =  1;
			$key = array();
		}
		
		$key = get_the_title( $ID );
		
		if ( $key == '') {
			
			$show_the_value = array();
			$key = array();
			
		}

		// Display the form, using the current value.
		echo '<table class="form-table">';
			echo '<tbody>';
				
			$allow_classes = get_option( 'allow_classes' );
			$count_classes = wp_count_posts('character_defaults');
			if ( $allow_classes == 'on' && $count_classes->publish > 0 ) {
				echo '<tr scope="row">';
				echo '<th>';
						echo '<label for="wpifw_parentClass">';_e( 'Use Class', 'wpifw_text' ); echo '</label> ';
				echo '</th>';
				echo '<td>';
				$useClass = $show_the_value[$key]['useClass'];
					echo '<input type="checkbox" id="wpifw_use_class_yes" name="wpifw_use_class_yes"';
					if ( $useClass == 'on') { echo ' checked '; }
					echo '>Yes</input>';
				echo '</td>';
				echo '</tr>';
				
				echo '<tr scope="row" id="classRow"';
				if ( $useClass != 'on') { echo ' style="display:none" '; }
				echo '>';
				$characterClass = $show_the_value[$key]['species'];
					echo '<th>';
						echo '<label for="wpifw_parentClass">';_e( 'Character Class', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
					echo '<select type="dropdown" id="wpifw_character_species" name="wpifw_character_species">';
						echo wpifw_utils::wpifw_generate_class_options( $characterClass );
					echo '</select> ';
					echo '</td>';
				echo '</tr>';
			}
				

				echo '<tr scope="row">';
				$characterAlignment = $show_the_value[$key]['alignment'];
					echo '<th>';
						echo '<label for="wpifw_character_alignment">'; _e( 'Alignment', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
					echo '<select type="dropdown" id="wpifw_character_alignment" name="wpifw_character_alignment">';
						
						echo '<option value="Friend" ';
							if ( $characterAlignment == 'Friend' ) { echo 'selected'; };
						echo ' >'; _e( 'Friend', 'wpifw_text' ); echo '</option>';
						echo '<option value="Foe" ';
							if ( $characterAlignment == 'Foe' ) { echo 'selected'; };
						echo ' >'; _e( 'Foe', 'wpifw_text' ); echo '</option>';
						echo '<option value="Neutral" ';
							if ( $characterAlignment == 'Neutral' ) { echo 'selected'; };
						echo ' >'; _e( 'Neutral', 'wpifw_text' ); echo '</option>';
						
					echo '</select> ';
					echo '</td>';
				echo '</tr>';	

				echo '<tr scope="row">';
				$characterGender = $show_the_value[$key]['gender'];
					echo '<th>';
						echo '<label for="wpifw_character_gender">'; _e( 'Gender', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
					echo '<select type="dropdown" id="wpifw_character_gender" name="wpifw_character_gender">';
						echo '<option value="Male" ';
							if ( $characterGender == 'Male' ) { echo 'selected'; };
						echo ' >'; _e( 'Male', 'wpifw_text' ); echo '</option>';
						echo '<option value="Female" ';
							if ( $characterGender == 'Female' ) { echo 'selected'; };
						echo ' >'; _e( 'Female', 'wpifw_text' ); echo '</option>';
						echo '<option value="It" ';
							if ( $characterGender == 'It' ) { echo 'selected'; };
						echo ' >'; _e( 'It', 'wpifw_text' ); echo '</option>';
						
					echo '</select> ';
					echo '</td>';
				echo '</tr>';	
				
				echo '<tr scope="row">';
				$characterBehaviour = $show_the_value[$key]['behaviour'];
					echo '<th>';
						echo '<label for="wpifw_character_behaviour">'; _e( 'Behaviour', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
					echo '<select type="dropdown" id="wpifw_character_behaviour" name="wpifw_character_behaviour">';
						echo '<option value="Attacker" ';
							if ( $characterBehaviour == 'Attacker' ) { echo 'selected'; };
						echo ' >'; _e( 'Attacker', 'wpifw_text' ); echo '</option>';
						echo '<option value="Defender" ';
							if ( $characterBehaviour == 'Defender' ) { echo 'selected'; };
						echo ' >'; _e( 'Defender', 'wpifw_text' ); echo '</option>';
					echo '</select> ';
					echo '</td>';
				echo '</tr>';
				
				//begin stats
				$useDefaults = $show_the_value[$key]['useDefaults'];
				
				if ( $allow_classes == 'on' && $count_classes->publish > 0 ) {
					
					echo '<tr scope="row">';
						echo '<th colspan="2">';
						echo '<hr />';
						echo '</th>';
						echo '</tr>';										
	
					if ( $useClass === 'on' ) { 	
						
						echo '<tr scope="row" id="use_stat_defaults" style="display:table-row">';
						
					} else {	
					
						echo '<tr scope="row" id="use_stat_defaults" style="display:none">';
						
					}
						echo '<th>';
							echo '<label for="wpifw_character_use">'; _e( 'Use Class defaults?', 'wpifw_text' ); echo '</label> ';
						echo '</th>';
						
						echo '<td>';
							echo '<input type="checkbox" id="wpifw_use_defaults_yes" name="wpifw_use_defaults_yes"';
							if ( $useDefaults === 'on' ) { echo ' checked'; };
							echo '></input> Yes ';
						echo '</td>';
						echo '</tr>';
						
						echo '</tbody>';		
						echo '</table>';
					
				}					
					
				echo '<table class="form-table" id="character-stats">';
						

				
				//Skill
				echo '<tbody>';	
				
				echo '<tr scope="row">';
				echo '<th>';
					echo '<label for="wpifw_character_skill">'; _e( 'Skill', 'wpifw_text' ); echo '</label> ';
				echo '</th>';
				echo '<td>';
				if (!$newCharacter) { $skillroll = $show_the_value[$key]['skill'][0];
				} else { $skillroll = "2d6"; }
					echo '<select type="dropdown" id="wpifw_skill_d1" name="wpifw_skill_d1">';
						echo wpifw_utils::wpifw_generate_dice_options( 'quantity', $skillroll );
					echo '</select>';
					echo ' x ';
					echo '<select type="dropdown" id="wpifw_skill_d2" name="wpifw_skill_d2">';
						echo wpifw_utils::wpifw_generate_dice_options( 'type', $skillroll );
					echo '</select>';
					echo ' Sided Dice ';
				echo '</td>';
				echo '</tr>';	
				
				//stamina
				echo '<tr scope="row">';
				echo '<th>';
					echo '<label for="wpifw_character_stamina">'; _e( 'Stamina', 'wpifw_text' ); echo '</label> ';
				echo '</th>';
				echo '<td>';
				if (!$newCharacter) { $staminaroll = $show_the_value[$key]['stamina'][0];
				} else { $staminaroll = "2d6"; }
					echo '<select type="dropdown" id="wpifw_stamina_d1" name="wpifw_stamina_d1">';
						echo wpifw_utils::wpifw_generate_dice_options( 'quantity', $staminaroll );
					echo '</select>';
					echo ' x ';
					echo '<select type="dropdown" id="wpifw_stamina_d2" name="wpifw_stamina_d2">';
						echo wpifw_utils::wpifw_generate_dice_options( 'type', $staminaroll );
					echo '</select>';
					echo ' Sided Dice ';				
				echo '</td>';
				echo '</tr>';
				
				//Luck
				echo '<tr scope="row">';
				echo '<th>';
					echo '<label for="wpifw_character_luck">'; _e( 'Luck', 'wpifw_text' ); echo '</label> ';
				echo '</th>';
				echo '<td>';
				if (!$newCharacter) { $luckroll = $show_the_value[$key]['luck'][0];
				} else { $luckroll = "2d6"; }	
					echo '<select type="dropdown" id="wpifw_luck_d1" name="wpifw_luck_d1">';
						echo wpifw_utils::wpifw_generate_dice_options( 'quantity', $luckroll );
					echo '</select>';
					echo ' x ';
					echo '<select type="dropdown" id="wpifw_luck_d2" name="wpifw_luck_d2">';
						echo wpifw_utils::wpifw_generate_dice_options( 'type', $luckroll );
					echo '</select>';
					echo ' Sided Dice ';				
				echo '</td>';
				echo '</tr>';
				
				//Gold
				echo '<tr scope="row">';
				if (!$newCharacter) { $goldroll = $show_the_value[$key]['gold'][0];
				} else { $goldroll = "2d6"; }
					echo '<th>';
						echo '<label for="wpifw_gold">';_e( 'Currency', 'wpifw_text' ); echo '</label> ';
					echo '</th>';
					echo '<td>';
						echo '<input type="checkbox" id="wpifw_has_gold" name="wpifw_has_gold" value="has_gold" ';
							
							if ( $goldroll != 0 ) {	echo ' checked'; }
							
						echo '/> '; _e( 'Can Have Currency', 'wpifw_text' ); echo '<br />'; 
						echo '<div id="gold_selector" style="display:';
							
							if ( $goldroll != 0 ) {	echo 'block">'; } else { echo 'none">';	}
						
							echo '<br /><select type="dropdown" id="wpifw_gold_d1" name="wpifw_gold_d1">';
								echo wpifw_utils::wpifw_generate_dice_options( 'quantity', $goldroll );
							echo '</select>';
							echo ' x ';
							echo '<select type="dropdown" id="wpifw_gold_d2" name="wpifw_gold_d2">';
								echo wpifw_utils::wpifw_generate_dice_options( 'type', $goldroll );
							echo '</select>';
							echo ' Sided Dice ';
							
						echo '</div>';
					echo '</td>';
				echo '</tr>';	

				
				//end stats	

			echo '</tbody>';		
		echo '</table>';

	}
	
	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_scene_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'wpifw_inner_custom_box', 'wpifw_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$show_the_value = get_post_meta($post->ID, '_follow_on_options', true);
		if ($show_the_value == '') { $show_the_value = array();	}

		$arrayCount = count( $show_the_value );	
		$follow_on_type = $show_the_value[0];
		
		if ( $follow_on_type == '' || !isset($follow_on_type) ) {
			$follow_on_type = 'no-options';
		}

		$allow_fight = get_option( 'allow_fight' );
		$allow_classes = get_option( 'allow_fight' );
		$allow_luck = get_option( 'allow_luck' );
		$allow_passphrase = get_option( 'allow_passphrase' );
		
		// Display the form, using the current value.
		echo '<div id="follow-on-options">';

			echo '<h4><label for="custom-meta-options">';
				_e( 'Required interaction type:', 'wpifw_text' );
			echo '</label></h4>';		
			
			echo '<div id="options-selector">';
			
				echo '<ul id="custom-meta-options">';
				
					echo '<li><input type="radio" id="none" name="selected" value="no-options"';
					if ( $follow_on_type == 'no-options' ) {
					echo ' checked'; } echo '>';
						_e( 'None', 'wpifw_text' );
					echo '</input></li>';
					
					echo '<li><input type="radio" id="choice" name="selected" value="choice-options"';
					if ( $follow_on_type == 'choice-options' ) {
					echo ' checked'; } echo '>';					
						_e( 'Choose', 'wpifw_text' );
					echo '</input></li>';
			
					if ( $allow_fight == 'on' ) {
					
						echo '<li><input type="radio" id="fight" name="selected" value="fight-options"';
						if ( $follow_on_type == 'fight-options' ) {
						echo ' checked'; } echo '>';					
							_e( 'Fight', 'wpifw_text' );
						echo '</input></li>';
					
					}
					
					if ( $allow_luck == 'on' ) {
			
						echo '<li><input type="radio" id="luck" name="selected" value="luck-options"';
						if ( $follow_on_type == 'luck-options' ) {
						echo ' checked'; } echo '>';
							_e( 'Luck', 'wpifw_text' );
						echo '</input></li>';
					
					}
					
					if ( $allow_passphrase == 'on' ) {
			
						echo '<li><input type="radio" id="passphrase" name="selected" value="passphrase-options"';
						if ( $follow_on_type == 'passphrase-options' ) {
						echo ' checked'; } echo '>';
							_e( 'Passphrase', 'wpifw_text' );
						echo '</input></li>';
					
					}
		
				echo '</ul>';
				
				echo '<br /><br />';
			
			echo '</div>';

			include 'wpifw_cpt_sceneOptions.php';			

	}
}
?>