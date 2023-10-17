<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->title,
            'teacher_id'=> $this->teacher_id,
            'teacher'=> $this->whenLoaded('teacher'),
            'class_id'=>$this->class_id,
            'class'=>$this->whenLoaded('classRoom'),
            'due_date'=>$this->due_date
        ];
    }
}
