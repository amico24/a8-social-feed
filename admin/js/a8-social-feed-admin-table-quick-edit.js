jQuery(document).ready(function($){
    //handles the quick edit functions in the settings tables
    console.log(table_data);
    let row = null;
    let edit_menu = null;
    let current_name = null;
    $(".asf-quick-edit").on('click', function(){
        console.log("edit button clicked");

        if(!$.isEmptyObject(row)){
            edit_menu.hide(); //make sure only one edit menu exists
            //doing this breaks the background colors of the rows
            //a problem for another time
            row.show();
            edit_menu = null;
            row = null;
            current_name = null;
        }

        row = $(this).parents("tr");
        row.hide();
        current_name = row.children('.column-name').contents().filter(function(){
            return this.nodeType == 3; //selects only the text in the html element
        }).text().trim();

        //console.log(table_data.user_data[current_name]);
        

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
                row.after(html);
                edit_menu = $(".inline-edit-row");
                break;
            case "profiles":
                html += '<tr class="inline-edit-row inline-edit-row-post quick-edit-row quick-edit-row-post inline-edit-post inline-editor">';
                    html += '<td colspan = "4">';
                        html += '<div class="inline-edit-wrapper" role="region">';
                        /* //removed "edit username" option bc idk what to do with it lmao
                            html += '<fieldset class="inline-edit-col-left">';
                                html += '<legend class="inline-edit-legend">Quick Edit</legend>';
                                html += '<div class="inline-edit-col">';
                                    html += '<label>';
                                        html += '<span class="title">Username</span>';
                                        html += '<span class="input-text-wrap">';
                                            html += '<form method="POST">';
                                                html += '<input type="text" id="edit_username" name="edit_username" placeholder="Edit Username">'; 
                                            html += '</form>';
                                        html += '</span>';
                                    html += '</label>';
                                html += '</div>';
                            html += '</fieldset>';*/
                            html += '<fieldset class="inline-edit-col-left inline-edit-categories">';
                                html += '<div class="inline-edit-col">';
                                    html += '<span class="title inline-edit-categories-label">Categories</span>';
                                    html += '<ul class="cat-checklist category-checklist">';
                                        $.each(table_data.categories, function(index, value) {
                                            html += '<li id="category-'+index+'" class="asf-categories">';
                                                html += '<label class="selectit">';
                                                    html += '<input value="'+value+'" type="checkbox" name="asf-category" id="'+value.replace(/\s+/g, '-')+'" data-catIndex = "'+index+'">'
                                                    //replace space in category with dash when setting id
                                                    html += value;
                                                html += '</label>';
                                            html += '</li>';
                                        });
                                    html += '</ul>';
                                html += '</div>';     
                            html += '</fieldset>';
                            html += '<fieldset class="inline-edit-col-center">';
                                html += '<div class="inline-edit-col">';
                                    html += '<label>';
                                        html += '<span class="title">Featured</span>';
                                        html += '<select id = "asf-featured" name="user_featured">';
                                            html += '<option value="0">No</option>';
                                            html += '<option value="1">Yes</option>';
                                        html += '</select>';
                                    html += '</label>';
                                    html += '<label>';
                                        html += '<span class="title">Homepage</span>';
                                        html += '<select id="asf-homepage" name="user_homepage">';
                                            html += '<option value="0">No</option>';
                                            html += '<option value="1">Yes</option>';
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
                row.after(html);
                edit_menu = $(".inline-edit-row");
                let current_user_data = table_data.user_data;
                current_user_data = current_user_data[current_name];
                let user_categories = current_user_data.category;
                console.log(user_categories);
                $.each(table_data.categories, function(index, value) {
                    console.log(value);
                    hasValue = (obj, value) => Object.values(obj).includes(value); //copy pasted off some fuckass website idk how this works
                    if(hasValue(user_categories, value)){
                        console.log("category matched");
                        edit_menu.find('#'+value.replace(/\s+/g, '-')).prop('checked', true);
                    }
                });
                edit_menu.find("#asf-featured").val(current_user_data.featured).change();
                edit_menu.find("#asf-homepage").val(current_user_data.homepage).change();
                break;
            default:
                break;
        }

        //row.after(html);
        //edit_menu = $(".inline-edit-row");
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

                console.log(current_name);
                
                $.ajax({
                    url:table_data.ajax_url,
                    data:{
                        action: 'edit_category',
                        category_data:{
                            new_name: new_name,
                            old_name: current_name
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
                        this.textContent = this.textContent.replace(current_name, new_name);
                    });//change name in table already

                    edit_menu = null;
                    row = null;
                    current_name = null;
                });
                break;
            case "profiles":
                let updated_user_data = {
                    'category': '',
                    'featured': '',
                    'homepage': ''
                };

                let user_cats = {};
                edit_menu.find('input[name="asf-category"]:checked').each(function() {
                    user_cats[$(this).data('catindex')] = this.value;
                    //console.log("this category: " + this.value)
                    //updated_user_data.category[$(this).data('catIndex')] = this.value;
                    updated_user_data.category = user_cats;
                });

                updated_user_data.featured = edit_menu.find('#asf-featured').val();
                updated_user_data.homepage = edit_menu.find('#asf-homepage').val();
                console.log(updated_user_data);

                $.ajax({
                    url:table_data.ajax_url,
                    data:{
                        action: 'edit_account',
                        account_data:{
                            username: current_name,
                            user_data: updated_user_data
                        }
                    },
                    dataType:'json',
                    type:'POST'
                }).done(function(response){
                    console.log("Post request sent successfully");
                    console.log(response);
                    edit_menu.hide();
                    row.show();

                    edit_menu = null;
                    row = null;
                    current_name = null;
                    location.reload();//reload page to update options
                });
                break;
            default:
                break;
        }
    });
});