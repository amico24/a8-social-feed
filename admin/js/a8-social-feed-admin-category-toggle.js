
jQuery(document).ready(function($){
    console.log("Checkbox Detected.");
    console.log(categories);
    $("body").on('click',".category-checkbox",function(){ //click func doesnt work for dynamic elements
        console.log("Button clicked");
        var category = $(this).parent().text();
        console.log(category);
        var users = categories[category]; //dot notation should also work here but this makes more sense to me
        console.log(users);

        var classes = $(this).closest(".shortcode").attr("class").split(/\s+/); //makes array of classes
        var id=classes[1]; //this is so jank i kinda hate it
        //assumes that the id is always the second class listed in the shortcode div
        console.log(id);

        if($(this).is(":checked")) {
            console.log("checked");
            $.each(users,function(index, value){
                $("."+id+" ."+value).show(300);
            })
        } else {
            console.log("not checked");
            $.each(users,function(index, value){
                $("."+id+" ."+value).hide(300);
            })
        }
    })
});