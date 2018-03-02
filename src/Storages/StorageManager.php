<?php

namespace App\Storages;


use App\Models\Note;
use App\Models\Course;
use App\Models\Profile;
use App\Models\TimeTable;
use App\Models\Complaint;
use App\Models\Announcement;
use App\Core\Database\IDatabase;
use App\Conversions\NoteConversion;
use App\Conversions\CourseConversion;
use App\Conversions\ProfileConversion;
use App\Conversions\TimeTableConversion;
use App\Conversions\ComplaintConversion;
use App\Conversions\AnnouncementConversion;

class StorageManager
{
    private $database;

    public function __construct(IDatabase $database)
    {
        $this->database = $database;
    }

    public function getProfileStorage() :IStorage
    {
        $model = Profile::empty();
        $conversion = new ProfileConversion;
        return new Storage($model, $this->database, $conversion);
    }

    public function getAnnouncementStorage() :IStorage
    {
        $model = Announcement::empty();
        $conversion = new AnnouncementConversion;
        return new Storage($model, $this->database, $conversion);
    }

    public function getComplaintStorage() :IStorage
    {
        $model = Complaint::empty();
        $conversion = new ComplaintConversion;
        return new Storage($model, $this->database, $conversion);
    }

    public function getNoteStorage() :IStorage
    {
        $model = Note::empty();
        $conversion = new NoteConversion;
        return new Storage($model, $this->database, $conversion);
    }

    public function getTimeTableStorage() :IStorage
    {
        $model = TimeTable::empty();
        $conversion = new TimeTableConversion;
        return new Storage($model, $this->database, $conversion);
    }

    public function getCourseStorage() :IStorage
    {
        $model = Course::empty();
        $conversion = new CourseConversion;
        return new Storage($model, $this->database, $conversion);
    }
}
