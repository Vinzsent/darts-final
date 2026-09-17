<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolYearController extends Controller
{
    public function index()
    {
        $schoolYears = SchoolYear::orderByDesc('shoo_year_id')->paginate(15);

        return view('school-years.index', compact('schoolYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_name' => ['required', 'string', 'max:255', 'unique:school_year,school_year_name'],
        ]);

        SchoolYear::create($validated);

        return redirect()->route('school-years.index')
            ->with('success', 'School year added successfully.');
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $validated = $request->validate([
            'school_year_name' => ['required', 'string', 'max:255', 'unique:school_year,school_year_name,' . $schoolYear->shoo_year_id . ',shoo_year_id'],
        ]);

        $schoolYear->update($validated);

        return redirect()->route('school-years.index')
            ->with('success', 'School year updated successfully.');
    }

    public function setCurrent(SchoolYear $schoolYear)
    {
        DB::transaction(function () use ($schoolYear) {
            SchoolYear::where('shoo_year_id', '!=', $schoolYear->shoo_year_id)->update(['current_year' => null]);
            $schoolYear->update(['current_year' => '1']);
        });

        return redirect()->route('school-years.index')
            ->with('success', $schoolYear->school_year_name . ' set as the current school year.');
    }

    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();

        return redirect()->route('school-years.index')
            ->with('success', 'School year deleted successfully.');
    }
}
