<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel API Crud</title>
    <style>
        body{
            padding-top: 50px;
        }
    </style>
    {{-- bootstrap css --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h4>Posts</h4>
                <span id="successMsg">

                </span>
                <table class="table table-bordered table-hover"> 
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        
                    </tbody>
                </table>
            </div>
            <div class="col-4">
                <h4>Create Post</h4>
                <span id="successMsg"></span>
                <form name="myForm">
                    <div class="form-group">
                        <label for="" title="">Title</label>
                        <input type="text" name="title" class="form-control">
                        <span id="titleError" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea rows="4" name="description" class="form-control"></textarea>
                        <span id="descError" class="text-danger"></span>
                    </div>
                    <button type="submit"  class="btn btn-primary btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>


    
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit post</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form action="" name="editForm" id="editModal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="" title="">Title</label>
                        <input type="text" name="title" class="form-control">
                        <span id="titleError" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea rows="4" name="description" class="form-control"></textarea>
                        <span id="descError" class="text-danger"></span>
                    </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        </div>
    </div>

    {{-- bootstrap js --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // READ
        var tableBody = document.getElementById('tableBody');
        var titleList = document.getElementsByClassName('titleList');
        var desList = document.getElementsByClassName('descList');
        var BtnList = document.getElementsByClassName('BtnList');
        var idList = document.getElementsByClassName('idList');
        

        axios.get('api/posts')
             .then(response => {
                response.data.forEach(function(item){
                    displayData(item);
                });
             })
             .catch(error => {
                console.log(error.response.config.method)
            });
            //CREATE
            var myForm = document.forms['myForm'];
            var titleInput = myForm['title'];
            var descriptionInput = myForm['description'];

            myForm.onsubmit = function(e){
                e.preventDefault();
                axios.post('api/posts',{
                    title: titleInput.value,
                    description: descriptionInput.value,
                })
                     .then(response =>{
                        

                        if(response.data.msg == "Data created successfully"){
                            //     document.getElementById('successMsg').innerHTML = `
                            // <div class="alert alert-success  alert-dismissible fade show" role="alert">
                            //     <strong>${response.data.msg}</strong>
                            //     <button type="button" class="close" data-dismiss='alert' aria-label="Close">
                            //         <span aria-hidden="true">&times</span>
                            //     </button>
                            // </div>`;
                            alertMsg(response.data.msg);
                            myForm.reset();

                            displayData(response.data[0]);
                            
                        }else{
                            var titleErr = document.getElementById('titleError');
                            var desErr = document.getElementById('descError');
                            if(titleInput.value == ''){
                                titleErr.innerHTML = '<i>'+response.data.msg.title+'</i>';
                            }else{
                                titleErr.innerHTML = "";
                            }
                            if(descriptionInput.value == ''){
                                desErr.innerHTML = '<i>'+response.data.msg.description+'</i>';
                            }else{
                                desErr.innerHTML = '';
                            }
                        }
                     })
                     .catch(err =>{
                        console.log(err.response)
                     });
            }

            //EDIT & UPDATE
            var editForm = document.forms['editForm'];
            var EditTitleInput = editForm['title'];
            var EditDesInput = editForm['description'];
            var postIdToUpdate;
            var oldTitle;
            //EDIT
            function editBtn(postId){
                postIdToUpdate = postId;
                axios.get('api/posts/'+postId)
                     .then(response =>{
                        EditTitleInput.value = response.data.title;
                        EditDesInput.value = response.data.description;
                        oldTitle = response.data.title;
                     })
                     .catch(err => console.log(err));
            }
            //UPDATE
            editForm.onsubmit = function(e){
                e.preventDefault();
                console.log(postIdToUpdate);
                axios.put('api/posts/'+postIdToUpdate, {
                    title: EditTitleInput.value,
                    description: EditDesInput.value,
                })
                     .then(response => {
                        console.log(response.data.msg)
                        console.log(oldTitle);
            //             document.getElementById('successMsg').innerHTML = `<div class="alert alert-success  alert-dismissible fade show" role="alert">
            //     <strong>${response.data.msg}</strong>
            //     <button type="button" class="close" data-dismiss='alert' aria-label="Close">
            //         <span aria-hidden="true">&times</span>
            //     </button>
            // </div>`;
            alertMsg(response.data.msg);
                $('#editModal').modal('hide');
                for(var i=0; i<titleList.length; i++){
                        if(titleList[i].innerHTML == oldTitle){
                            titleList[i].innerHTML = EditTitleInput.value;
                            desList[i].innerHTML = EditDesInput.value;
                        }
                     }
                     })
                     .catch(err => console.log(err));
            }

            //DELELTE
            function deleteBtn(postId){
                if(confirm('Are you sure to delete?')){
                    axios.delete('api/posts/'+postId)
                .then(response => {
                    console.log(response.data.deletedPost);
                    alertMsg(response.data.msg);
                    for(var i=0; i<titleList.length; i++){
                        if(titleList[i].innerHTML == response.data.deletedPost.title){
                            titleList[i].style.display = 'none';
                            BtnList[i].style.display = 'none';
                            idList[i].style.display = 'none';
                            desList[i].style.display = 'none';
                        }
                    }
                })
                .catch(err => console.log(err));
                }
            }

            //Helper Function
            function displayData(data){
                tableBody.innerHTML += `
                        <tr>
                            <td class="idList">${data.id}</td>
                            <td class="titleList">${data.title}</td>
                            <td class="descList">${data.description}</td>
                            <td class="BtnList">
                                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#editModal" onclick="editBtn(${data.id})">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteBtn(${data.id})">Delete</button>
                            </td>
                        </tr>`
            }
            function alertMsg(message){
                document.getElementById('successMsg').innerHTML = `<div class="alert alert-success  alert-dismissible fade show" role="alert">
                    <strong>${message}</strong>
                    <button type="button" class="close" data-dismiss='alert' aria-label="Close">
                        <span aria-hidden="true">&times</span>
                    </button>
                </div>`;
            }
    </script>

</body>
</html>