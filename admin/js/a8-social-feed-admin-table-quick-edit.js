jQuery(document).ready(function($){
    //only on categories for now
    //need to make sure only one edit menu exists at one time
    console.log(table_data);
    let row = null;
    let edit_menu = null;
    $(".asf-quick-edit").on('click', function(){
        console.log("edit button clicked");

        if(!$.isEmptyObject(row)){
            edit_menu.hide(); //make sure only one edit menu exists
            //doing this breaks the background colors of the rows
            //a problem for another time
            row.show();
            edit_menu = null;
            row = null;
        }

        row = $(this).parents("tr");
        row.hide();
        let html = "";
        //yes the formatting here looks stupid but this makes more sense to me
        switch(table_data.type){
            case "categories":
                html += '<tr class="inline-edit-row">';
                    html += '<td colspan = "3">';
                        html += '<div class="inline-edit-wrapper">';
                            html += '<fieldset class="inline-edit-col-left">';
                                html += '<legend class="inline-edit-legend">Quick Edit</legend>';
                                html += '<div class="inline-edit-col">';
                                    html += '<label>';
                                        html += '<span class="title">Category Name</span>';
                                        html += '<span class="input-text-wrap">';
                                            html += '<form method="POST">';
                                                html += '<input type="text" id="edit_cat_name" name="edit_cat_name" placeholder="Edit Category Name">'; 
                                            html += '</form>';
                                        html += '</span>';
                                    html += '</label>';
                                html += '</div>';
                            html += '</fieldset>';                
                            html += '<div class="submit inline-edit-save">';
                                html += '<button type="button" class="button button-primary save asf-quick-edit-save">Update</button>';
                                html += '<button type="button" class="button cancel asf-quick-edit-cancel">Cancel</button>';
                            html += '</div>';
                        html += '</div>';
                    html += '</td>';
                html += '</tr>';
                break;
            case "profiles":
                html += '<tr class="inline-edit-row inline-edit-row-post quick-edit-row quick-edit-row-post inline-edit-post inline-editor">';
                    html += '<td colspan = "4">';
                        html += '<div class="inline-edit-wrapper" role="region">';
                            html += '<fieldset class="inline-edit-col-left">';
                                html += '<legend class="inline-edit-legend">Quick Edit</legend>';
                                html += '<div class="inline-edit-col">';
                                    html += '<label>';
                                        html += '<span class="title">Username</span>';
                                        html += '<span class="input-text-wrap">';
                                            html += '<form method="POST">';
                                                html += '<input type="text" id="edit_cat_name" name="edit_cat_name" placeholder="Edit Category Name">'; 
                                            html += '</form>';
                                        html += '</span>';
                                    html += '</label>';
                                html += '</div>';
                            html += '</fieldset>';
                            html += '<fieldset class="inline-edit-col-center inline-edit-categories">';
                                html += '<div class="inline-edit-col">';
                                    html += '<span class="title inline-edit-categories-label">Categories</span>';
                                    html += '<ul class="cat-checklist category-checklist">';
                                        $.each(table_data.categories, function(index, value) {
                                            html += '<li id="category-'+index+'" class="popular-category">';
                                                html += '<label class="selectit">';
                                                html += '<input value="'+value+'" type="checkbox" name="user_category['+index+']" id="'+value+'"> '+value+'</label>';
                                            html += '</li>';
                                        });
                                    html += '</ul>';
                                html += '</div>';     
                            html += '</fieldset>';
                            html += '<fieldset class="inline-edit-col-right">';
                                html += '<div class="inline-edit-col">';
                                    html += '<label>';
                                        html += '<span class="title">Featured</span>';
                                        html += '<select name="user_featured">';
                                            html += '<option value="default">No</option>';
                                            html += '<option value="true">Yes</option>';
                                        html += '</select>';
                                    html += '</label>';
                                    html += '<label>';
                                        html += '<span class="title">Homepage</span>';
                                        html += '<select name="user_homepage">';
                                            html += '<option value="default">No</option>';
                                            html += '<option value="true">Yes</option>';
                                        html += '</select>';
                                    html += '</label>';
                                html += '</div>';
                            html += '</fieldset>';
                            html += '<div class="submit inline-edit-save">';
                                html += '<button type="button" class="button button-primary save asf-quick-edit-save">Update</button>';
                                html += '<button type="button" class="button cancel asf-quick-edit-cancel">Cancel</button>';
                            html += '</div>';
                        html += '</div>';
                    html += '</td>';
                html += '</tr>';
                break;
            default:
                break;
        }

        row.after(html);
        edit_menu = $(".inline-edit-row");
    });

    $("body").on('click',".asf-quick-edit-cancel",function(){
        edit_menu = $(this).parents("tr");
        edit_menu.hide();
        row.show();
        edit_menu = null;
        row = null;
    });

    $("body").on('click',".asf-quick-edit-save",function(){
        let edit_menu = $(this).parents("tr");
        /*
        edit_menu.hide();
        row.show();*/
        
        switch(table_data.type){
            case "categories":
                let new_name = $('#edit_cat_name').val(); //get input
                let old_name = row.children('.column-name').contents().filter(function(){
                    return this.nodeType == 3; //selects only the text in the html element
                }).text().trim();

                console.log(old_name);
                
                $.ajax({
                    url:table_data.ajax_url,
                    data:{
                        action: 'edit_category',
                        category_data:{
                            new_name: new_name,
                            old_name: old_name
                        }
                    },
                    dataType:'json',
                    type:'POST'
                }).done(function(response){
                    console.log("Post request sent successfully");
                    console.log(response);
                
                    edit_menu.hide();
                    row.show();
                    row.children('.column-name').contents().filter(function(){
                        return this.nodeType == 3;
                    }).each(function(){
                        this.textContent = this.textContent.replace(old_name, new_name);
                    });//change name in table already

                    edit_menu = null;
                    row = null;
                });
                break;
            case "profiles":
                //this next
                break;
            default:
                break;
        }
        

    });
});