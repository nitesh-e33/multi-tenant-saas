<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']); // index could be api-only if needed
        $this->middleware('ensure.active.company')->only(['show', 'someOtherScopedActions']);
    }

    // List companies for the authenticated user
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $companies = $user->companies()->get();

        return response()->json($companies);
    }

    public function store(CompanyStoreRequest $request)
    {
        $user = $request->user();
        $company = $user->companies()->create($request->validated());
        // If user has no active company, set this as active automatically
        if (! $user->active_company_id) {
            $user->active_company_id = $company->id;
            $user->save();
        }
        if ($request->expectsJson()) {
            return response()->json($company, Response::HTTP_CREATED);
        }
        return redirect()->route('dashboard')->with('success', 'Company created successfully!');
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $company = $user->companies()->where('id', $id)->first();

        if (! $company) {
            return response()->json(['message' => 'Company not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($company);
    }

    public function edit(Company $company)
    {
        // Ownership check (important) — ensures user can't edit others' companies
        if ($company->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('companies.edit', compact('company'));
    }

    public function update(CompanyUpdateRequest $request, $id)
    {
        $user = $request->user();
        $company = $user->companies()->where('id', $id)->first();

        if (! $company) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Company not found'], Response::HTTP_NOT_FOUND);
            }
            return redirect()->route('dashboard')->with('error', 'Company not found');
        }

        $company->update($request->validated());

        if ($request->expectsJson()) {
            return response()->json($company);
        }

        return redirect()->route('dashboard')->with('success', 'Company updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $company = $user->companies()->where('id', $id)->first();

        if (! $company) {
            return response()->json(['message' => 'Company not found'], Response::HTTP_NOT_FOUND);
        }

        // If company is active, unset active_company_id
        if ($user->active_company_id === $company->id) {
            $user->active_company_id = null;
            $user->save();
        }

        $company->delete();

        // return response()->json(['message' => 'Deleted']);
        return redirect()->route('dashboard')->with('success', 'Company Deleted Successfully!');
    }

    // Switch active company
    public function switchActive(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|integer|exists:companies,id',
        ]);

        $user = $request->user();
        $company = $user->companies()->where('id', $data['company_id'])->first();

        if (! $company) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Company not found or not owned by user'], Response::HTTP_FORBIDDEN);
            }
            return back()->with('error', 'Company not found or not owned by you');
        }

        $user->active_company_id = $company->id;
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Active company set', 'active_company' => $company]);
        }

        return redirect()->route('dashboard')->with('success', 'Active company switched successfully!');
    }
}
