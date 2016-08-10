<?php global $shortcode_tags; ?>
<div id="theme-help" class="i-am-a-fancy-admin">
	<div class="container">
		<h2>Help</h2>

		<?php if ($updated):?>
		<div class="updated fade"><p><strong><?=__( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<div class="sections">
			<ul>
				<li class="section"><a href="#posting">Posting</a></li>
				<li class="section"><a href="#uids">UIDs</a></li>
				<li class="section"><a href="#shortcodes">Shortcodes</a></li>
			</ul>
		</div>
		<div class="fields help-page">
			<ul>

				<li class="section" id="posting">
					<h3>Intro</h3>
					<p>
						The goal of the help section is to familiarize yourself with the UCF Brand website and the different types of
						content that can be created. This should also help you understand the flow of content creation for the UCF Brand website.
					</p>
					<h3>Content Types</h3>
					<p>
						The primary types of content that can be created in this site are <strong>Pages</strong> and <strong>Unit Identifiers (UIDs)</strong>.
					</p>
					<p>
						<strong>Pages (WordPress default)</strong> will be used to setup most of the content with the aid of shortcodes.
					</p>
					<p>
						<strong>UIDs</strong> are images that colleges and departments use for letterhead, websites and printed materials.
					</p>
					<p>
						<strong>Shortcodes</strong> are small snippets of code, wrapped in square brackets [], that do some function or add some
						predefined content to your story content.  On this site, we use them to add blockquotes, callout boxes, sidebars, and
						more to Story and Issue content.
					</p>
				</li>

				<li class="section" id="uids">
					<h3>UIDs</h3>
					<p>
						<strong>UIDs</strong> are images that colleges and departments use for letterhead, websites and printed materials.
					</p>
					<h3>User UID Search and Request Process</h3>
					<ul>
						<li>Users are required to login to search or request a new UID.</li>
						<li>The user is directed to search for a UID to determine if the UID is availabe.</li>
						<li>If the user finds the UID they are looking for they can download the UID.</li>
						<li>If the UID is not found the user may request a new UID via the UID Request Form displayed below the search results.</li>
						<li>An email is sent to the designers with the UID information.</li>
					</ul>

					<h3>Approval Process</h3>
					<ul>
						<li>The designer should navigate to the Forms -> Request A Unit Identifier -> Entries section of the WordPress Admin.</li>
						<li>Click on the appropriate UID Request</li>
						<li>
							On the right sidebar a Notifications box will be available
							<ul>
								<li>Check to box next to "Admin Notification" to resend the request information to designers.</li>
								<li>Check the box next to the "Request Approved" to send an email to the requester that the UID has been approved and is available to download. Include the link to the new UID in the notes section at the bottom.</li>
								<li>Check the box next to the "Request Denied" to send an email to the requester notifying them to request has been denied.</li>
							</ul>
						</li>
						<li>Prior to approving the request create the new UID images using Illustrator.</li>
						<li>Create a new UID post and upload a PNG and ZIP file containing a PNG, EPS and PDF.</li>
						<li>Select the Form Request Entry assoiated with the UID post.</li>
					</ul>

				</li>

				<li class="section" id="shortcodes">
					<h3>Shortcodes</h3>
					<p>
						<strong>Shortcodes</strong>, in a nutshell, are <em>shortcuts</em> for displaying or doing various things.  They look like small snippets of code,
						wrapped in square brackets [], but using them requires no knowledge of HTML, CSS, or other code languages.
					</p>

					<p><strong>Navigation:</strong></p>
					<ul class="section-nav">
						<li>
							<a href="#shortcodes-basics">Shortcode Basics</a>
						</li>
						<li>
							<a href="#shortcodes-callout">Callout</a>
						</li>
						<li>
							<a href="#shortcodes-sidebar">Sidebar</a>
						</li>
						<li>
							<a href="#shortcodes-heading">Heading</a>
						</li>
						<li>
							<a href="#shortcodes-uidsearch">UIDSearch</a>
						</li>
					</ul>

					<h3 id="shortcodes-basics">Shortcode Basics</h3>

					<p>
						When a shortcode is added to post content, it will be displayed in the editor as a code snippet, but when you view the post as a preview or live post,
						you will see the output of what the shortcode is programmed to do, with the <strong>content</strong> and <strong>attributes</strong> you provide.
					</p>
					<p>
						The shortcodes listed below have a <strong>starting tag</strong> ([my-shortcode]) and an <strong>ending tag</strong> ([/my-shortcode]).  Depending on
						the shortcode used, such as the [lead] and [blockquote] shortcodes, <strong>content</strong> between the starting and ending tags is used.  Other
						shortcodes, like the [slideshow] shortcode, do not use any content between the starting and ending tags.
					</p>
					<p>
						Some shortcodes use <strong>attributes</strong> to define extra options for whatever the given shortcode does.  For example, the [callout] and [sidebar]
						shortcodes have a "background" attribute, which lets you set a custom background color for the callout box or sidebar.
					</p>

					<p>
						The custom available shortcodes for this site, as well as their available attributes and examples, are listed below.  For information about adding
						shortcodes to post content, please visit the Adding Content via Shortcodes section of the <a href="#stories">Story documentation</a>.
					</p>

					<h3 id="shortcodes-callout">callout</h3>
					<p>
						Creates a full-width box that breaks out of the primary content column to help text or other content stand out.
					</p>

					<h4>Content</h4>
					<p>
						This shortcode <strong>requires content</strong> between its starting and ending tags.<br/>
						<strong>Any text, media or other shortcodes</strong> are permitted between the shortcode tags.
					</p>

					<h4>Attributes</h4>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Attribute</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Background Color</td>
							<td>background</td>
							<td>The color to be used for the background of the callout box.</td>
							<td>#f0f0f0</td>
						</tr>
						<tr>
							<td>Image</td>
							<td>image</td>
							<td>Image float right in the callout</td>
							<td></td>
						</tr>
						<tr>
							<td>Content Alignment</td>
							<td>content_align</td>
							<td>Content text can be aligned left, right or center.</td>
							<td>center</td>
						</tr>
						<tr>
							<td>Enable Affixing</td>
							<td>affix</td>
							<td>
								When set to 'true', enables affixing on the callout box.  (true or false)<br><br>
								Multiple affixed callouts are supported.<br><br>
								Affixing will only activate if the callout box doesn't take up an excessive amount of vertical
								screen real estate (50% on screen sizes greater than 767px wide, 30% on screen sizes 767px and below.)
							</td>
							<td>false</td>
						</tr>
						<tr>
							<td>CSS Classes</td>
							<td>css_class</td>
							<td>(Optional) CSS classes to apply to the callout. Separate classes with a space.</td>
							<td>n/a</td>
						</tr>
					</table>

					<h4>Examples</h4>
					<pre><code>[callout background="#e1e1e1"]&lt;p&gt;Lorem ipsum dolor sit amet.&lt;/p&gt;[/callout]</code></pre>
					<pre><code>[callout content_align="right"]&lt;h2&gt;Heading in callout&lt;/h2&gt;&lt;p&gt;Lorem ipsum dolor sit amet.&lt;/p&gt;[/callout]</code></pre>

					<h3 id="shortcodes-sidebar">sidebar</h3>
					<p>
						Creates a floating block that other content wraps around.  Used for text or media that is related to a group of text, but doesn't fit within
						the normal paragraph form of the content.
					</p>

					<h4>Content</h4>
					<p>
						This shortcode <strong>requires content</strong> between its starting and ending tags.<br/>
						<strong>Any text, media or other shortcodes</strong> are permitted between the shortcode tags.
					</p>

					<h4>Atttributes</h4>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Attribute</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Background Color</td>
							<td>background</td>
							<td>The background color of the sidebar element.</td>
							<td>#f0f0f0</td>
						</tr>
						<tr>
							<td>Position</td>
							<td>position</td>
							<td>Horizontal position of the box (left or right).</td>
							<td>right</td>
						</tr>
						<tr>
							<td>Content Alignment</td>
							<td>content_align</td>
							<td>Align content left, right or center.</td>
							<td>left</td>
						</tr>
					</table>

					<h4>Examples</h4>
					<pre><code>[sidebar background="#e1e1e1" position="left"]
&lt;h2&gt;Heading in sidebar&lt;/h2&gt;
&lt;p&gt;This is related content but does not fit inside the main paragraph.&lt;p&gt;
[/sidebar]</code></pre>
					<pre><code>[sidebar background="#e1e1e1" content_align="center"]This is centered content.[/sidebar]</code></pre>

					<h3 id="shortcodes-heading">heading</h3>
					<p>
						Creates a full-width box that breaks out of the primary content column to create headings specifically for the homepage.
					</p>

					<h4>Content</h4>
					<p>
						This shortcode <strong>requires content</strong> between its starting and ending tags.<br/>
						Designed to contain an <strong>h2</strong> and <strong>a (link)</strong> tag between the shortcode tags.
					</p>

					<h4>Attributes</h4>
					<table>
						<tr>
							<th scope="col">Name</th>
							<th scrop="col">Attribute</th>
							<th scope="col">Description</th>
							<th scope="col">Default Value</th>
						</tr>
						<tr>
							<td>Background Image</td>
							<td>background_image</td>
							<td>The image to be used for the background of the heading.</td>
							<td></td>
						</tr>
					</table>

					<h4>Example</h4>
					<pre><code>[heading background="path/to/image.png"]&lt;h2&gt;Large Heading&lt;/h2&gt;&lt;a href="link.html"&gt;Link text&lt;/a&gt;[/heading]</code></pre>

					<h3 id="shortcodes-uidsearch">UID Search</h3>
					<p>
						Inserts the necessary code for the UID Search component.
					</p>

					<h4>Example</h4>
					<pre><code>[uid-search][/uid-search]</code></pre>

				</li>
			</ul>
		</div>
	</div>
</div>