<?php

		
		/*
		 * Display the ENDING options
		 *
		 */
	
		echo '<div id="no-options" style="display:';
			if ( $follow_on_type == 'no-options' ) {
				echo 'block">';
			} else {
				echo 'none">';
			}
			echo '<hr>';
			echo '<h4>';
				_e( 'The End', 'wpifw_text' );
			echo '</h4>';
			_e( 'You may wish to add a footnote... (optional) ', 'wpifw_text' );
			echo '<br /><textarea id="ending" name="ending">';
				if($show_the_value[1]['ending'] != '') echo  $show_the_value[1]['ending'];
			echo '</textarea>';
		echo '</div>';
		
		/*
		 * Display the CHOICE options
		 *
		 */
				
		echo '<div id="choice-options" style="display:';
			if ( $follow_on_type == 'choice-options' ) {
				echo 'block">';
			} else {
				echo 'none">';
			}
			echo '<hr>';
			echo '<h4>';
				_e( 'Multiple Choice', 'wpifw_text' );
			echo '</h4>';
			echo '<table id="destination-table" class="wp-list-table widefat AdminTable">';
				echo '<thead><tr><th width="2.2em"><input class="button button-small" type="submit" id="delete-row" value="Delete"></th><th width="80%">';
					_e( 'Question / Statement', 'wpifw_text' );
				echo '</th><th width="20%">';
					_e( 'Destination Scene', 'wpifw_text' );
				echo '</th></tr></thead><tbody id="table-content">';
				if ( $arrayCount > 1 && $follow_on_type == 'choice-options' ) {
					for($i=1; $i<$arrayCount; $i++) { 
						$destination = $show_the_value[$i]['destination']; ?>
						<tr><td width="2.2em"><input type="checkbox"></input></td>
						<td><input type="text" name="choice[]" value="<?php if($show_the_value[$i]['choice'] != '') echo  $show_the_value[$i]['choice']; ?>" />
                        <div class="row-actions"><a>Edit</a> | <a>Delete</a></div></td>
						<td><select type="dropdown" name="destination[]" id="destination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $destination ); ?></select></td></tr>
					<?php }
				} else { ?><tr><td width="2.2em"><input type="checkbox"></input></td>
						<td><input type="text" name="choice[]" value="" />
                        <div class="row-actions"><a>Edit</a> | <a>Delete</a></div></td>
						<td><select type="dropdown" name="destination[]" id="destination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act(); ?></select></td></tr>
					<?php 	
				}
			echo '</tbody><tfoot><tr><td colspan="3"><input type="submit" id="add-choice" class="button" value="Add"></input></td></tr></tfoot></table></div>';
		
		/*
		 * Display the FIGHT options
		 *
		 */
		 
		if ( $allow_fight == 'on' ) { 
		
			echo '<div id="fight-options" style="display:';
				if ( $follow_on_type == 'fight-options' ) {
					echo 'block">';
				} else {
					echo 'none">';
				}
				echo '<hr>';
				echo '<h4>';
					_e( 'Fight Options', 'wpifw_text' );
				echo '</h4>';
				echo '<table id="combatant-table" class="wp-list-table widefat AdminTable">';
					echo '<thead><tr><th width="2.2em"><input class="button button-small" type="submit" id="delete-row" value="Delete"></input></th><th>';
						_e( 'Combatants', 'wpifw_text' );
					echo '</th></thead><tbody>';
					$numCombatants = count($show_the_value[1]['combatants']);
					if ( $numCombatants > 0 ) {
						for( $i = 0; $i < $numCombatants; $i++ ) { 
							$thisCombatant = $show_the_value[1]['combatants'][$i];
							if ( strpos($thisCombatant,'random-') === 0 ) {
							?><tr><td><input type="checkbox"></input></td>
							<td><select type="dropdown" name="combatants[]" class="combatant combatant<?php echo $i; ?>" id="combatant-dropdown" style="float:left" disabled><?php wpifw_utils::wpifw_get_combatants_in_act( $thisCombatant ); ?></select>
							<select type="dropdown" name="combatants[]" class="randomClass"><option>Select...</option><?php wpifw_utils::wpifw_generate_class_options( $thisCombatant ); ?></select></div></td></tr><?php
							} else {
							?><tr><td><input type="checkbox"></input></td>
							<td><select type="dropdown" name="combatants[]" class="combatant combatant<?php echo $i; ?>" id="combatant-dropdown" style="float:left"><?php wpifw_utils::wpifw_get_combatants_in_act( $thisCombatant ); ?></select></div></td></tr><?php
							}
						 }
					} else { ?>
							<tr><td width="2.2em"><input type="checkbox"></input></td>
							<td><select type="dropdown" name="combatants[]" class="combatant combatant0" id="combatant-dropdown" style="float:left"><?php wpifw_utils::wpifw_get_combatants_in_act(); ?></select></td></tr>
					<?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><input type="submit" id="add-combatant" class="button" value="Add"></input>
                            </td>
                            </tr>
                        </tfoot>
                    </table> 
                    <table class="form-table">
                    	<tbody>
                        	<tr>
                            	<th><?php _e( 'Permit fleeing the scene', 'wpifw_text' ); ?></th>
								<td><input name="flee" type="checkbox"<?php if ( $show_the_value[2]['flee'] == 'on' ) { echo ' checked ';  } ; ?>></input></td>
                            </tr>
                            
                            <tr>
                            	<th><?php _e( 'Flee destination', 'wpifw_text' ); ?></th>
                                <?php $fleeDestination = $show_the_value[2]['fleeDestination']; ?>
								<td><select type="dropdown" name="fleeDestination" id="fleeDestination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $fleeDestination ); ?></select></td>
                            </tr>
                            
                            <tr>
                            	<th><?php _e( 'Chance of success', 'wpifw_text' ); ?></th>
                                <?php $successChance = $show_the_value[2]['successChance']; ?>
								<td>
                                    <ul>
                                        <li><input type='radio' name='successChance' id='10' value='10'<?php if ( $show_the_value[2]['successChance'] == '10' ) { echo ' checked ';  } ; ?>>10%</input></li>
                                        <li><input type='radio' name='successChance' id='20' value='20'<?php if ( $show_the_value[2]['successChance'] == '20' ) { echo ' checked ';  } ; ?>>20%</input></li>
                                        <li><input type='radio' name='successChance' id='30' value='30'<?php if ( $show_the_value[2]['successChance'] == '30' ) { echo ' checked ';  } ; ?>>30%</input></li>
                                        <li><input type='radio' name='successChance' id='40' value='40'<?php if ( $show_the_value[2]['successChance'] == '40' ) { echo ' checked ';  } ; ?>>40%</input></li>
                                        <li><input type='radio' name='successChance' id='50' value='50'<?php if ( $show_the_value[2]['successChance'] == '50' ) { echo ' checked ';  } ; ?>>50%</input></li>
                                        <li><input type='radio' name='successChance' id='60' value='60'<?php if ( $show_the_value[2]['successChance'] == '60' ) { echo ' checked ';  } ; ?>>60%</input></li>
                                        <li><input type='radio' name='successChance' id='70' value='70'<?php if ( $show_the_value[2]['successChance'] == '70' ) { echo ' checked ';  } ; ?>>70%</input></li>
                                        <li><input type='radio' name='successChance' id='80' value='80'<?php if ( $show_the_value[2]['successChance'] == '80' ) { echo ' checked ';  } ; ?>>80%</input></li>
                                        <li><input type='radio' name='successChance' id='90' value='90'<?php if ( $show_the_value[2]['successChance'] == '90' ) { echo ' checked ';  } ; ?>>90%</input></li>
                                    </ul>    
                                </td>
                            </tr>
                            
                            <tr>
                            	<th><?php _e( 'Permit shows of mercy', 'wpifw_text' ); ?></th>
                                <?php $mercyDestination = $show_the_value[2]['mercyDestination']; ?>
								<td><input name="mercy" type="checkbox"<?php if ( $show_the_value[2]['mercy'] == 'on' ) { echo ' checked ';  } ; ?>></input></td>
                            </tr>
                            
                            <tr>
                            	<th><?php _e( 'Mercy destination', 'wpifw_text' ); ?></th>
								<td><select type="dropdown" name="mercyDestination" id="mercyDestination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $mercyDestination ); ?></select></td>
                            </tr>
                        
                        	<tr scope="row">
                        		<th><?php echo '<label for="wpifw_victory">';_e( 'If victorious, go to: ', 'wpifw_text' ); ?></label></th> 
                        		<?php $victory = $show_the_value[2]['victory']; ?>
                                <td><select type="dropdown" name="victory" id="destination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $victory ); ?></select></td>
                        	</tr>
                            <tr>
                        		<th><?php echo '<label for="wpifw_defeat">';_e( 'If defeated, go to: ', 'wpifw_text' ); ?></label></th> 
                        		<?php $defeat = $show_the_value[2]['defeat']; ?>
                                <td><select type="dropdown" name="defeat" id="destination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $defeat ); ?></select></td>
							</tr>
						</tbody> 
					</table>    	
					<div style="display:none"><select type="dropdown" name="combatants[]" class="randomClass"><option>Select...</option><?php wpifw_utils::wpifw_generate_class_options(); ?></select></div>
				<?php echo '</div>'; 
			
		}
		
		/*
		 * Display the LUCK options
		 *
		 */
		 
		if ( $allow_luck == 'on' ) { 		 
				
			echo '<div id="luck-options" style="display:';
				if ( $follow_on_type == 'luck-options' ) {
					echo 'block">';
				} else {
					echo 'none">';
				}
				echo '<hr>';
				echo '<h4>';
					_e( 'Luck Options', 'wpifw_text' );
				echo '</h4>';
			?>
                    <table class="form-table">
                    	<tbody>
                            <tr>
                            	<th><?php _e( 'Chance of success', 'wpifw_text' ); ?></th>
                                <?php $successChance = $show_the_value[2]['successChance']; ?>
								<td>
                                    <ul>
                                        <li><input type='radio' name='successChance' id='10' value='10'<?php if ( $show_the_value[1]['successChance'] == '10' ) { echo ' checked ';  } ; ?>>10%</input></li>
                                        <li><input type='radio' name='successChance' id='20' value='20'<?php if ( $show_the_value[1]['successChance'] == '20' ) { echo ' checked ';  } ; ?>>20%</input></li>
                                        <li><input type='radio' name='successChance' id='30' value='30'<?php if ( $show_the_value[1]['successChance'] == '30' ) { echo ' checked ';  } ; ?>>30%</input></li>
                                        <li><input type='radio' name='successChance' id='40' value='40'<?php if ( $show_the_value[1]['successChance'] == '40' ) { echo ' checked ';  } ; ?>>40%</input></li>
                                        <li><input type='radio' name='successChance' id='50' value='50'<?php if ( $show_the_value[1]['successChance'] == '50' ) { echo ' checked ';  } ; ?>>50%</input></li>
                                        <li><input type='radio' name='successChance' id='60' value='60'<?php if ( $show_the_value[1]['successChance'] == '60' ) { echo ' checked ';  } ; ?>>60%</input></li>
                                        <li><input type='radio' name='successChance' id='70' value='70'<?php if ( $show_the_value[1]['successChance'] == '70' ) { echo ' checked ';  } ; ?>>70%</input></li>
                                        <li><input type='radio' name='successChance' id='80' value='80'<?php if ( $show_the_value[1]['successChance'] == '80' ) { echo ' checked ';  } ; ?>>80%</input></li>
                                        <li><input type='radio' name='successChance' id='90' value='90'<?php if ( $show_the_value[1]['successChance'] == '90' ) { echo ' checked ';  } ; ?>>90%</input></li>
                                    </ul>    
                                </td>
                            </tr>
                        
                        	<tr scope="row">
                        		<th><?php echo '<label for="wpifw_success">';_e( 'If successful, go to: ', 'wpifw_text' ); ?></label></th> 
                        		<?php $success = $show_the_value[1]['success']; ?>
                                <td><select type="dropdown" name="success" id="destination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $success ); ?></select></td>
                        	</tr>
                            <tr>
                        		<th><?php echo '<label for="wpifw_fail">';_e( 'If unsuccessful, go to: ', 'wpifw_text' ); ?></label></th> 
                        		<?php $fail = $show_the_value[1]['fail']; ?>
                                <td><select type="dropdown" name="fail" id="destination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $fail ); ?></select></td>
							</tr>
                            <tr>
                        		<th><?php echo '<label for="wpifw_decline">';_e( 'If declined, go to: ', 'wpifw_text' ); ?></label></th> 
                        		<?php $decline = $show_the_value[1]['decline']; ?>
                                <td><select type="dropdown" name="decline" id="destination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $decline ); ?></select></td>
							</tr>
						</tbody> 
					</table>    	
				<?php echo '</div>'; 
		
		}
		
		/*
		 * Display the PASSPHRASE options
		 *
		 */
		 
		if ( $allow_passphrase == 'on' ) { 		 
				
			echo '<div id="passphrase-options" style="display:';
				if ( $follow_on_type == 'passphrase-options' ) {
					echo 'block">';
				} else {
					echo 'none">';
				}
				echo '<hr>';
				echo '<h4>';
					_e( 'Passphrase Options', 'wpifw_text' );
				echo '</h4>';
			?>
                    <table class="form-table">
                    	<tbody>
	                        
                            <tr>
                            	<th><?php _e( 'Passphrase', 'wpifw_text' ); ?></th>
								<?php $passphrase = $show_the_value[1]['passphrase']; ?>
								<td><input name="passphrase" id="passphrase" value="<?php echo $passphrase ?>" /></td>
                            </tr>

                            <tr>
                            	<th><?php _e( 'Correct destination', 'wpifw_text' ); ?></th>
                                <?php $correctDestination = $show_the_value[1]['correctDestination']; ?>
								<td><select type="dropdown" name="correctDestination" id="correctDestination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $correctDestination ); ?></select></td>
                            </tr>
                            <tr>
                            	<th><?php _e( 'Incorrect destination', 'wpifw_text' ); ?></th>
                                <?php $incorrectDestination = $show_the_value[1]['incorrectDestination']; ?>
								<td><select type="dropdown" name="incorrectDestination" id="incorrectDestination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $incorrectDestination ); ?></select></td>
                            </tr>
                            <tr>
                            	<th><?php _e( 'Decline destination', 'wpifw_text' ); ?></th>
                                <?php $declineDestination = $show_the_value[1]['declineDestination']; ?>
								<td><select type="dropdown" name="declineDestination" id="declineDestination-dropdown"><?php wpifw_utils::wpifw_get_scenes_in_act( $declineDestination ); ?></select></td>
                            </tr>
                            


                            
						</tbody> 
					</table>    	
				<?php echo '</div>'; 
		
		}