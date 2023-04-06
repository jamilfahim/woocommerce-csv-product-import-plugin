// Quick Add Post AJAX
var quickAddBtn = document.querySelector('#fronted-post-submit');

if( quickAddBtn ) {
    quickAddBtn.addEventListener("click", function(){
        //alert('clicked');
        var title = document.querySelector('.fronted-post [name="post-title"]').value;
        var content =  document.querySelector('.fronted-post [name="post-content"]').value;
        var ourPostData = {
            "title" :title,
            "content" :content,
            "status": "publish"
        }
      alert(title);
      alert(content);
     

        var createPost = new XMLHttpRequest();

        createPost.open("POST", additionalaData.home_url+'/wp-json/wp/v2/posts/');
        createPost.setRequestHeader('X-WP-Nonce', additionalaData.nonce);
        createPost.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        createPost.send(JSON.stringify(ourPostData));
        createPost.onreadystatechange = function() {
            if(createPost.readyState == 4) {
                if( createPost.status == 201 ) {
                    document.querySelector('.fronted-post [name="post-title"]').value = '';
                    document.querySelector('.fronted-post [name="post-content"]').value = '';
                } else {
                    alert('Error - Try again.');
                }
            }
        }
    });
}