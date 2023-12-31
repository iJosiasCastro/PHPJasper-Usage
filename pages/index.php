<?php
require '../functions/generate_file_tree.php';

require '../vendor/autoload.php';

function generateFileTree($dir) {
    $result = "<ul>";

    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            $filePath = $dir . '/' . $file;
            if (is_dir($filePath)) {
                $result .= "<li><strong>$file</strong>";
                $result .= generateFileTree($filePath); // Recursively generate file tree for subdirectories
                $result .= "</li>";
            } else {
                $result .= "<li>$file</li>";
            }
        }
    }

    $result .= "</ul>";
    return $result;
}

function generateReport($param, $input, $connect_db) {

	$filename = 'report_'.time(); 
	$ext = '.pdf';
    $input = realpath('../reports') . '/' . $input.'.jasper';  
    $output = realpath('../reports') . '/' . $filename;

	if($connect_db){
		$options = [
			'format' => ['pdf'],
			'locale' => 'en',
			'params' =>  $param,
			'db_connection' => [
				'driver' 	=> 'mysql',
				'username' 	=> 'root',
				'password' 	=> '""',
				'host' 		=> '127.0.0.1',
				'database'	=> 'test',
				'port' 		=> '3306'
			]
		];
	}else{
		$options = [
			'format' => ['pdf'],
			'locale' => 'en',
			'params' =>  $param,
		];
	}


	$jasper = new PHPJasper\PHPJasper;
	$jasper->process(
		$input,
		$output,
		$options
	)->execute();

	header('Content-Type: application/pdf');
	header('Content-Disposition: inline; filename="'.basename($output.$ext).'"');
	header('Content-Length: ' . filesize($output.$ext));

	readfile($output.$ext);
	unlink($output.$ext);

	flush();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $parameters = [];

    if (isset($_POST['params']) && !empty($_POST['params'])) {
        $parameters = json_decode($_POST['params'], true);
    }

    $input = $_POST['fileName'];
    $connect_db = isset($_POST['connect_db']) ? true : false;

    $result = generateReport($parameters, $input, $connect_db);

    if ($result === true) {
        echo "Report generated successfully!";
    } else {
        echo "Report generation failed.";
    }
}

// Specify the destination folder for displaying the file tree
$destinationFolder = realpath('../reports');
$fileTree = generateFileTree($destinationFolder);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">
    <title>Jasper Report Compiler, File Tree, and Report Generation Form</title>
    <style>
        ul {
            list-style: none;
        }

        ul ul {
            padding-left: 20px;
        }

        strong {
            color: #0070f3;
        }
    </style>
</head>
<body class="bg-gray-200 py-10">
    <div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-4">Generate Jasper Report</h2>
        <?php if (!empty($error)): ?>
            <div class="mb-4 text-red-500">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="mb-4">
                <label for="fileName" class="block text-gray-700">File Name (without extension):</label>
                <input type="text" id="fileName" name="fileName" value="test_params" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="params" class="block text-gray-700">Parameters:</label>
                <textarea id="params" rows="5" name="params" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:border-blue-500">{"myString": "text"}</textarea>
            </div>
            <div class="flex items-center gap-1 mb-4">
                <input type="checkbox" id="connect_db" name="connect_db">
                <label for="connect_db">Enviar conexión a base de datos</label>
            </div>
            <div class="">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none">Generate Report</button>
            </div>
            
        </form>
    </div>
    
    <div class="max-w-3xl mx-auto mt-8 bg-white p-6 rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-4">File Tree</h2>
        <?php echo $fileTree; ?>
    </div>

    <div class="max-w-3xl mx-auto mt-8">
        <a href="./compile.php" class="text-blue-500">Go to the compiler</a>
    </div>
</body>
</html>
