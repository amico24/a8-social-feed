//i hate javascript GRAAGGGHHHHH

//handles API calls
jQuery(document).ready(function($){

    console.log(php_data);
    console.log(php_data.length);
    $.ajaxSetup({ cache: true });
    //var checkbox_exists = false;
    var clicked_id = '';
    var post_count = 0;
    $("body").on('click',".load-button",function(){
        let clicked_classes = $(this).attr("class").split(/\s+/);
        clicked_id=clicked_classes[1]; //assumes id is second class
    });

    for(var i = 0; i < php_data.length; i++){
        let args = php_data[i];
        console.log("User List: "+args.user_list + " " + args.user_list.length);
        console.log("Client id: "+args.ig_client_id);
        //checkbox_exists = args.shortcode_atts.checkbox;
        $.getScript('https://connect.facebook.net/en_US/sdk.js', function(){
            FB.init({
                appId: '1695938317479097',
                version: 'v18.0'
            });
            let user_data = {};
            for (let username of args.user_list){
                FB.api(
                    '/'+args.ig_user_id,
                    'GET',
                    {"fields":'business_discovery.username('+username+'){username,name,profile_picture_url,biography,media.limit('+args.max_posts+'){media_url,id,username,timestamp,media_type,permalink}}',
                    "access_token":args.access_token},
                    function(response) {
                        user_data[username]=response;
                        console.log(user_data[username]);
                        console.log("Current object Length: " + Object.keys(user_data).length);

                        if(Object.keys(user_data).length == args.user_list.length){
                            if(!isNaN(args.shortcode_atts.length) && parseInt(Number(args.shortcode_atts.length)) == args.shortcode_atts.length && !isNaN(parseInt(args.shortcode_atts.length, 10))){
                                post_count = args.shortcode_atts.length;
                            } else {
                                post_count = args.max_posts * args.user_list.length;
                            }
                            var user_data_json = JSON.stringify(user_data);
                            console.log(args.shortcode_atts);
                            $.ajax({
                                url:args.ajax_url,
                                data:{
                                    action: 'insta_api',
                                    atts: args.shortcode_atts,
                                    identifier: args.shortcode_identifier,
                                    api_data: user_data_json //user_data not sending gRRrRrRr
                                },
                                dataType:'json',
                                type:'POST'
                            }).done(function(response){
                                console.log("Post request sent successfully");
                                console.log(response);
                                console.log(post_count);
                    
                                $(".feed."+args.shortcode_identifier).append(response.data);
                                $(".placeholder."+args.shortcode_identifier).hide();
                            });
                        }
                    }
                );
            }
            $("body").on('click',".load-button",function(){
                if (post_count >= args.abs_max_posts){
                    $(".feed_content."+args.shortcode_identifier).append("<p>Page post limit reached.</p>");
                    $(".load-button-section."+args.shortcode_identifier).hide();
                } else {
                    let more_user_data = {};
                    for (let username of args.user_list){
                        FB.api( 
                        '/'+args.ig_user_id,
                        'GET',
                        {"fields":'business_discovery.username('+username+'){username,name,profile_picture_url,biography,media.after('+user_data[username]["business_discovery"]["media"]["paging"]["cursors"]["after"]+').limit('+args.max_posts+'){media_url,id,username,timestamp,media_type,permalink}}',
                        "access_token":args.access_token},
                        function(response) {
                            more_user_data[username] = response;
                            console.log(user_data[username]);
                            console.log("Current object Length: " + Object.keys(user_data).length);

                            if(Object.keys(more_user_data).length == args.user_list.length){
                                var user_data_json = JSON.stringify(more_user_data);
                                //console.log(user_data_json);
                                post_count += args.max_posts * args.user_list.length;
                                $.ajax({
                                    url:args.ajax_url,
                                    data:{
                                        action: 'insta_api_more',
                                        atts: args.shortcode_atts,
                                        identifier: args.shortcode_identifier,
                                        api_data: user_data_json
                                    },
                                    dataType:'json',
                                    type:'POST'
                                }).done(function(response){
                                    console.log("Post request sent successfully");
                                    console.log(response);
                                    console.log(post_count);

                                    if(args.shortcode_identifier.indexOf(clicked_id) >= 0){
                                        $(".feed_content."+args.shortcode_identifier).append(response.data);
                                    }
                                }); 
                            }
                        }
                    );
                    }
                }
            });
        });
    }

    
});


