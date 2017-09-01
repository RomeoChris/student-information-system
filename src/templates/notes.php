<div class="container">
    <ol class="breadcrumb" style="margin-bottom: 15px;">
        <li><a href="/dashboard/">Home</a></li>
        <li class="active">Notes</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php 
                if (count($notesResults)) 
                {                
            ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" style="padding: 10px;">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Lecturer</th>
                            <th>Course</th>
                            <th>Semester</th>
                            <th>Date Added</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>						
                        <?php
                            foreach ($notesResults as $notesResult) 
                            {
                            	echo '<tr>';
                            	echo '<td>' . $notesResult->name . '</td>';
                            	echo '<td>' . $notesResult->lecturer . '</td>';
                            	echo '<td>' . $notesResult->course . '</td>';
                            	echo '<td>' . $notesResult->semester . '</td>';
                            	echo '<td>' . explode(' ', $notesResult->timestamp)[0] . '</td>';
                            	echo '<td><a href="' . $notesResult->rootpath . '" download><span class="fa fa-download"></a></span></td>';
                            	echo '</tr>';
                            }                            
                        ?>
                    </tbody>
                </table>
            </div>
            <?php 
                }
                else
                {
                	echo '<h1><div class="alert alert-info text-center"><p>There are no notes currently</p></div></h1>';
                }                
            ?>
        </div>
    </div>
</div>