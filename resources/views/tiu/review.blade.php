<div class="card shadow-lg">
    <div class="card-header py-3 korek-color-bg-gradient text-white">
        <h6 class="m-0 font-weight-bold text-white" id="form_title">Reviews</h6>
    </div>
</div>
@foreach($cards as $card)

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{$card->title}}</h6>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col" class="table-header-col col-md-2">Name</th>
                    <th scope="col" class="table-header-col col-md-1">Quantity</th>
                    <th scope="col" class="table-header-col col-md-1">Submit</th>
                    <th scope="col" class="table-header-col col-md-3">Point</th>
                </tr>
                </thead>
                <tbody>
                @foreach($card->questions as $question)
                    <tr question-id="{{$question->id}}" point={{$question->point}}>
                        <th scope="row">{{$question->name}}</th>
                        <td><input oninput="handleInputChange(this)" class="form-control" required type="number" name="quantity" id="quantity" min="0"  value="{{$question->userFeedbacks->quantity??0}}"/></td>
                        <td><button type="button" class="btn btn-primary btn-default" onclick="save_question(this)">Save</button></td>
                        <td id="score">{{$question->point * ($question->userFeedbacks->quantity??0)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endforeach

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Files Upload</h6>
    </div>
    <div class="card-body">
        <form id="uploadForm" action="/file-upload" method="POST" enctype="multipart/form-data">
            <div style="width: 22rem;">
                <label for="formFileMultiple" class="form-label">
                    @foreach($files as $file)
                        <a href="{{ asset('storage/files/' . $file->path) }}" target="_blank"><img style="height: 40px;width: 40px;border: 1px solid #555;" src="{{ asset('storage/files/' . $file->path) }}"></a>
                    @endforeach
                </label>
                <input class="form-control" type="file" name="files[]" id="files" multiple>
            </div>
            <button class="btn btn-primary btn-default mt-2" type="submit" id="submitFiles">Save</button>
        </form>
    </div>
</div>

<script>
    $(function (){
        $('#uploadForm').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: 'file-upload',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data)
                },
            });
        });
    });
    function handleInputChange(event) {
        const element=$(event).parent().parent();
        const point=element.attr('point')
        let input= element.find('input#quantity').val();
        let score= element.find('td#score');
        score.text(input*point)
    }
    function save_question(event){
        const element=$(event).parent().parent();
        const question_id=element.attr('question-id')
        let quantity= element.find('input#quantity');
        if(quantity.val()>0 || quantity.val()!=="") {
            $.ajax({
                url: '/reviews',
                type: 'POST',
                data:{
                    'question_id':question_id,
                    'quantity':quantity.val()
                },
                success: function (res) {
                    if(res.success) {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }else{
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "There is an error, data not saved",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
            });
        }
        else{
            quantity.val(0);
            swal.fire({
                title: "Error",
                text: "Make sure form filled properly",
                icon: "error"
            });
        }
    }
</script>
