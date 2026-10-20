<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the main admin dashboard (default landing page).
     */
    public function dashboard(): View
    {
        return view('admin.dashbord_index');
    }

    /**
     * Display the admin dashboard index view.
     */
    public function dashbordIndex(): View
    {
        return view('admin.dashbord_index');
    }

    /**
     * Display the add employee view.
     */
    public function addEmploye(): View
    {
        return view('admin.add_employe');
    }

    /**
     * Display all events view.
     */
    public function allEvents(): View
    {
        return view('admin.all_events');
    }

    /**
     * Display all memories view.
     */
    public function allMemories(): View
    {
        return view('admin.all_memories');
    }

    /**
     * Display all users view.
     */
    public function allUsers(): View
    {
        return view('admin.all_users');
    }

    /**
     * Display the bulk email view.
     */
    public function bulkemail(): View
    {
        return view('admin.bulkemail');
    }

    /**
     * Display the create memory view.
     */
    public function createMemory(): View
    {
        return view('admin.create-memory');
    }

    /**
     * Display the edit memory view (shares the create/edit memory form).
     */
    public function editMemory(): View
    {
        return view('admin.create-memory');
    }

    /**
     * Display the create my event view.
     */
    public function createMyEvent(): View
    {
        return view('admin.create_my_event');
    }

    /**
     * Display the create package view.
     */
    public function createPackage(): View
    {
        return view('admin.create_package');
    }

    /**
     * Display the edit employee view.
     */
    public function editEmploye(): View
    {
        return view('admin.edit_employe');
    }

    /**
     * Display the edit my event view.
     */
    public function editMyEvent(): View
    {
        return view('admin.edit_my_event');
    }

    /**
     * Display my events view.
     */
    public function myEvents(): View
    {
        return view('admin.my_events');
    }

    /**
     * Display my packages view.
     */
    public function myPackage(): View
    {
        return view('admin.my_package');
    }

    /**
     * Display payments view.
     */
    public function payments(): View
    {
        return view('admin.payments');
    }

    /**
     * Display profile settings view.
     */
    public function profileSettings(): View
    {
        return view('admin.profile_settings');
    }

    /**
     * Display reports view.
     */
    public function reports(): View
    {
        return view('admin.reports');
    }

    /**
     * Display view employee view.
     */
    public function viewEmploye(): View
    {
        return view('admin.view_employe');
    }

    /**
     * Display view my event view.
     */
    public function viewMyEvent(): View
    {
        return view('admin.view_my_event');
    }

    /**
     * Display view packages view.
     */
    public function viewPakages(): View
    {
        return view('admin.view_pakages');
    }
}
