(function ($) {
   //create post
   $("#jq-fronted-post-submit").click(function () {
      // Get the value from input
      var title = $('.fronted-post [name="post-title"]').val();
      var content = $('.fronted-post [name="post-content"]').val();

      alert(title);
      alert(content);

      var postData = {
         title: title,
         content: content,
         status: "publish",
      };

      $.ajax({
         beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", ajax_var.nonce);
         },
         type: "POST",
         url: ajax_var.root_url + "/wp-json/wp/v2/posts/",
         data: postData,
         dataType: "json",
         success: function (response) {
            console.log(response);
            $("#post-published").show();
         },
         error: function (error) {
            console.log("Something went wrong", error);
         },
      });
   });

   //delete post
   $(".delete").click(function (event) {
      alert("deleted");
      var targetLi = $(event.target).parents("li");
      console.log(targetLi);

      $.ajax({
         beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", ajax_var.nonce);
         },
         type: "DELETE",
         url: ajax_var.root_url + "/wp-json/wp/v2/posts/" + targetLi.data("id"),
         success: function (response) {
            console.log(response);
         },
         error: function (error) {
            console.log("Something went wrong", error);
         },
      });
   });

   
   $(".edit").click(function (event) {
      $(".fronted-blog").addClass("hide");
      $(".fronted-post-hide").addClass("hide");
      $(".fronted-post-update").show();

      // Get the value from input
      var targetLi = $(event.target).parents("li");
      var id = targetLi.data("id");
      // Get the value from input
      alert(id);
      var updatePost = [];
      alert(updatePost.post.post_title);

      $("#update-post-title").val(post.post.post_title);
      $("#update-post-content").val(post.post.post_title);

      jQuery.ajax({
         url: ajax_var.root_url + "/wp-json/softtechit/v1/posts/" + id,
         method: "GET",
         timeout: 60,
         async: false,
         dataType: "json",
         success: function (result) {
            updatePost = result;
            console.log(result.post.post_title);
            //alert(result);
         },
      });

      $("#jq-fronted-post-edit").click(function () {
         var title = $('.fronted-post [name="update-post-title"]').val();
         var content = $('.fronted-post [name="update-post-content"]').val();
         //$("#user_id").val(id);
         var postData = {
            ID: id,
            title: title,
            content: content,
            status: "publish",
         };

         $.ajax({
            beforeSend: function (xhr) {
               xhr.setRequestHeader("X-WP-Nonce", ajax_var.nonce);
            },
            type: "POST",
            url: ajax_var.root_url + "/wp-json/wp/v2/posts/" + id,
            data: postData,
            dataType: "json",
            success: function (response) {
               console.log(response);
               alert(title);
               alert(content);

               $("#post-updated").show();
            },
            error: function (error) {
               console.log("Something went wrong", error);
            },
         });
      });
   });
})(jQuery);
