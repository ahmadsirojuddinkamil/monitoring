<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LOgging</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container w-75 mt-5 mb-5 border">
        <h3 class=" mt-4">Get all</h3>
        <form action="/data-logging" method="POST">
            @csrf

            <div>
                <label for="type" class="form-label">All data logging</label>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h3 class=" mt-4">Get by type</h3>
        <form action="/data-logging/type" method="POST">
            @csrf

            <div class="mb-3">
                <label for="type" class="form-label">Logging by type</label>
                <select class="form-select" name="type">
                    <option value="local">local</option>
                    <option value="testing">testing</option>
                    <option value="production">production</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h3 class=" mt-4">Get by time</h3>
        <form action="/data-logging/type/time" method="POST">
            @csrf

            <div class="mb-3">
                <label for="type" class="form-label">Type logging</label>
                <select class="form-select" name="type">
                    <option value="local">local</option>
                    <option value="testing">testing</option>
                    <option value="production">production</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="time-start" class="form-label">Time Start</label>
                <input type="datetime-local" class="form-control" id="time-start" name="time_start">
            </div>

            <div class="mb-3">
                <label for="time-end" class="form-label">Time End</label>
                <input type="datetime-local" class="form-control" id="time-end" name="time_end">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h3 class=" mt-4">Delete all</h3>
        <form action="/data-logging/delete" method="POST">
            @csrf
            @method('DELETE')

            <div class="mb-3">
                <label for="type" class="form-label">Delete all logging</label>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h3 class=" mt-4">Delete by type</h3>
        <form action="/data-logging/delete/type" method="POST">
            @csrf
            @method('DELETE')

            <div class="mb-3">
                <label for="type" class="form-label">Type logging</label>
                <select class="form-select" name="type">
                    <option value="local">local</option>
                    <option value="testing">testing</option>
                    <option value="production">production</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <h3 class=" mt-4">Delete by time</h3>
        <form action="/data-logging/delete/type/time" method="POST" class=" mb-3">
            @csrf
            @method('DELETE')

            <div class="mb-3">
                <label for="type" class="form-label">Type logging</label>
                <select class="form-select" name="type">
                    <option value="local">local</option>
                    <option value="testing">testing</option>
                    <option value="production">production</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="time-start" class="form-label">Time Start</label>
                <input type="datetime-local" class="form-control" id="time-start" name="time_start">
            </div>

            <div class="mb-3">
                <label for="time-end" class="form-label">Time End</label>
                <input type="datetime-local" class="form-control" id="time-end" name="time_end">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>