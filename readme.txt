=== Hikari Featured Comments ===
Contributors: shidouhikari 
Donate link: http://Hikari.ws/wordpress/#donate
Tags: comment, feature, featured, highlight, threaded, bury, buried, children, metabox, CSS
Requires at least: 2.9.0
Tested up to: 3.0.1
Stable tag: 0.02.00

It adds 3 new custom fields to comments (Featured, Buried, Children buried), allowing you to add special properties to each of them.

== Description ==

Have you ever wanted to highlight a valuable comment among all others? Or not let that troll 6-paragraphs comment take more space than it deserve? Or even hide a whole thread of offtipics?

Well, now you can!


**<a href="http://hikari.ws/featured-comments/">Hikari Featured Comments</a>** is a simple plugin that adds 3 new custom fields to comments, allowing you to add special properties to each of them.

With this feature available, you can query any comment to know if it has any of those properties flagged, and if so you can take special actions within your theme or another plugin.


= Features =

There are 3 special properties available, you can give them any semantic you like, but default meanings are as follows:
* *Featured*: a comment flagged as featured should be highlighted from all others. You can give it special CSS styles from your theme to make it more visible among all comments, and flag any valuable comment as so.
* *Buried*: a buried comment is a comment you don't wanna just delete, but also don't want it as visible as all others. You can use it to hide offtopic comments which you don't want to perpetuate.
* *Children/Threaded buried*: this one has similar meaning to the former, but it should be applied to nested/threaded/children comments of the flagged comment.

Note that any comment can be independently flagged as *Buried* and *Children buried*, therefore a Children buried comment shouldn't have special styles applied to itself. In the same way, you can also have a featured AND buried comment, having both styles should be applied together!

**Hikari Featured Comments** provides a special metabox in comment edit page (/wp-admin/comment.php?action=editcomment&c=XXXX) where you can flag these properties, and it automatically attach new classes (<code>featured</code>, <code>buried</code>, <code>children_buried</code>) to comments in frontend.


== Installation ==

**Hikari Featured Comments** requires at least *Wordpress 2.9* and *PHP5* to work.

You can use the built in installer and upgrader, or you can install the plugin manually.

1. Download the zip file, upload it to your server and extract all its content to your <code>/wp-content/plugins</code> folder. Make sure the plugin has its own folder (for exemple  <code>/wp-content/plugins/hikari-featured-comments/</code>).
2. Activate the plugin through the 'Plugins' menu in WordPress admin page.
3. That's it! Go to any comment you wanna flag, open its edit page and see the new metabox available!
4. At first, no change will be visible. You must edit your theme's <code>comment.php</code> or <code>style.css</code> to add any feature your creativity creates based on these properties.


= Upgrading =

If you have to upgrade manually, simply delete <code>hikari-featured-comments</code> folder and follow installation steps again.

= Uninstalling =

If you go to plugins list page and deactivate the plugin, it's comments metadata stored in database
 will remain stored and won't be deleted.

In future version I'm gonna include an option to delete properties metadata from database.


== Frequently Asked Questions ==

= Ok, I installed your plugin and as expected nothing happens when I flag a comment as featured. What now? =

Now your creativity defines what happens! These properties open a variety of possibilities, in both Web Design and Web Development areas!

If you wanna give special CSS styles to flagged comments, just go to your theme's <code>style.css</code>, search for the section where comments styles are and add new styles there. A very simple exemple:

<code>
.comment.featured{
	weight: bold;
}
.comment.buried{
	display: none;
}
</code>


= Nice! This gives me some great ideas, but that would require more advanced code... What can you offer? =

If you wanna develop extra features based on these properties, I provide 3 functions to help you:

* <code>hkFC_is_comment_featured($comment)</code>
* <code>hkFC_is_comment_buried($comment)</code>
* <code>hkFC_is_comment_children_buried($comment)</code>


Just pass a comment object or ID as parameter to receive a boolean return saying if that comment is flagged or not, you can also pass a *null* parameter to use current comment. There are also 3 filters you can use to change a comment property just before any code retrieves that data, without needing to hack the plugin:

* <code>apply_filters( 'hkFC_is_comment_featured', $featured, $comment_id )</code>
* <code>apply_filters( 'hkFC_is_comment_buried', $buried, $comment_id )</code>
* <code>apply_filters( 'hkFC_is_comment_children_buried', $children_buried, $comment_id )</code>


== Screenshots ==

1. The custom metabox in comment's edit page, together with another metabox, just below comment's content textarea


You can see live exemples in the plugin's homepage: <a href="http://hikari.ws/featured-comments/">http://Hikari.ws/featured-comments/</a>

== Changelog ==

= 0.02 - 04/10/2010 =
* First public release.

== Upgrade Notice ==

= 0.02 and above =
If you have to upgrade manually, simply delete <code>hikari-featured-comments</code> folder and follow installation steps again.
