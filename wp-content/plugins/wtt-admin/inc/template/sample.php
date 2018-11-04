<div class="hdContent">
	
	<div class="two_third">
		
		<h2>How To Use</h2>
		<h3>Adding A New Quiz</h3>
		<ul>
			<li>Select <strong>Quizzes</strong> in the left menu.</li>
			<li>Enter the name of the quiz, then click on Add A New Quiz. This will add it to the list on the
				right.
			</li>
			<li>Click the name of the newly added quiz to set the quiz options such as the needed pass
				percentage
			</li>
		</ul>
		
		<h3>Adding New Questions</h3>
		<ul>
			<li>Select <strong>Add New Question</strong> in the left menu.</li>
			<li>Enter the question as the title.</li>
			<li>You can have up to ten (10) answers per question. Make sure to select which answer is the
				correct one.
			</li>
			<li>In the right sidebar, you will see a metabox called <strong>Quizzes</strong> with a list of all
				quizzes you have created. Select the quiz that this question belongs to.
			</li>
			<li>Selecting <strong>Question as Title</strong> will use the question as a title / heading.</li>
			<li>Selecting <strong>Paginate</strong> will create a new jQuery page staring with this question.
			</li>
		</ul>
		
		
		<h3>Using A Quiz</h3>
		<ul>
			<li>Questions Bank uses shortcodes to render a quiz, so you can place a quiz almost anywhere on your
				site!
			</li>
			<li>To find the shortcode for a quiz, select <strong>Quizzes</strong> in the left menu.</li>
			<li>You will now see a list of all of your quizzes in a table, with the shortcode listed.</li>
			<li>Copy and paste the shortcode into any page or post you want to render that quiz!</li>
		</ul>
	
	</div>
	
	<div class="one_third last">
		
		
		<p>Questions Bank is provided for free by Dylan of <a href="http://harmonicdesign.ca">Harmonic
				Design</a>.</p>
		
		<div class="hdQuCallout">
			&nbsp;
		
		</div>
		
		<div class="clearboth"></div>
		
		<?php add_thickbox(); ?>
		<h3>Changing Question Order</h3>
		<ul>
			<li>For now, there is no super easy way to order questions - they will be ordered by creation date
				(most recent first).
			</li>
			<li>In the meantime, I strongly recommend installing and using the <a
					href="plugin-install.php?tab=plugin-information&plugin=intuitive-custom-post-order&TB_iframe=true&width=772&height=905"
					class="thickbox">Intuitive Custom Post Order</a> plugin.
			</li>
			<li>I have ZERO affiliation with this plugin: It's just a temporary solution until I integrate
				similar functionality into Questions Bank.
			</li>
		</ul>
		
		<h3>Need help?</h3>
		<ul>
			<li>This is a <em>free</em> Premium WordPress plugin, so we just get pure unfiltered satisfaction
				knowing that you use and love Questions Bank.
			</li>
			<li>So, loyal Questions Bank lover, if you need help, please don't hesitate to leave us a message or
				question on the <a href="https://wordpress.org/support/plugin/hd-quiz">official WordPress
					Questions Bank Support Forum</a>, or on our own support page at <a
					href="http://harmonicdesign.ca/hd-quiz/">Harmonic Design</a>.
			</li>
		</ul>
	
	</div>
	
	
	<div class="clearboth"></div>
	
	<h2>Settings</h2>
	<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		
		<table cellpadding="20px" cellspacing="0" class="hdTable">
			<tr>
				<td valign="top"><a class="hdQuTooltip">? <span><b></b>This is needed to allow Facebook to share dynamic content - the results of the quiz. If this is not used, then Facebook will share the page without the results.</span></a>Facebook
					APP ID
				</td>
				<td valign="top">
					<input type="text" class="widefat" name="<?php echo $data_field_name1; ?>"
					       id="<?php echo $data_field_name1; ?>1"
					       value="<?php echo stripslashes($opt_val1); ?>">
					<p><a href="http://harmonicdesign.ca/hd-quiz-create-a-facebook-app/">click here to learn how
							to quickly create a facebook app</a></p>
				</td>
			</tr>
			<tr/>
			<td valign="top"><a class="hdQuTooltip">? <span><b></b>This is used if you have sharing results enabled. The sent tweet will contain a mention to your account for extra exposure.</span></a>Twitter
				Handle
			</td>
			<td valign="top">
				<input type="text" class="widefat" name="<?php echo $data_field_name2; ?>"
				       id="<?php echo $data_field_name2; ?>1" value="<?php echo stripslashes($opt_val2); ?>">
				<p>please <strong>do NOT</strong> include the @ symbol.</p>
			</td>
			</tr>
			<tr/>
			<td valign="top"><a class="hdQuTooltip">? <span><b></b>The Next button is used on quizzes with pagination enabled</span></a>Next
				Button Text
			</td>
			<td valign="top">
				<input type="text" class="widefat" name="<?php echo $data_field_name3; ?>"
				       id="<?php echo $data_field_name3; ?>1" value="<?php echo stripslashes($opt_val3); ?>">
			</td>
			</tr>
			<tr/>
			<td valign="top"><a class="hdQuTooltip">?
					<span><b></b>The button a user clicks to submit the quiz</span></a>Finish Button Text
			</td>
			<td valign="top">
				<input type="text" class="widefat" name="<?php echo $data_field_name4; ?>"
				       id="<?php echo $data_field_name4; ?>1" value="<?php echo stripslashes($opt_val4); ?>">
			</td>
			</tr>
			<tr/>
			<td valign="top"><a class="hdQuTooltip">? <span><b></b>Each question is prefixed by the word 'Question' and the question #. Rename 'question' here.</span></a>Rename
				'Question'.
			</td>
			<td valign="top">
				<input type="text" class="widefat" name="<?php echo $data_field_name5; ?>"
				       id="<?php echo $data_field_name5; ?>1" value="<?php echo stripslashes($opt_val5); ?>">
			</td>
			</tr>
			<tr>
				<td valign="middle">&nbsp;</td>
				<td valign="middle"><input type="submit" class="button button-primary" style="float:right"
				                           value="UPDATE"/></td>
			</tr>
		</table>
	</form>

</div>