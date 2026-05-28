@extends('portal.app')

@push('styles')
<style>
    .portal-documents-page .portal-documents-section-title {
        border-bottom: 1px solid #e8e8e8;
        margin: 24px 0 12px;
        padding: 0 0 6px;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #333;
    }

    .portal-documents-page .portal-document-card {
        margin: 5px;
        padding: 20px 12px 20px 80px;
        min-height: 120px;
    }

    .portal-documents-page .portal-document-card__meta {
        font-size: 12px;
        line-height: 1.5;
        color: #888;
    }

    .portal-documents-page .portal-document-card__meta strong {
        color: #666;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="main-container portal-documents-page">
    <div class="section">
        <div class="col-12 col-xl-10 mx-auto">
            <h1 class="title-2 center" style="margin-top: -40px; border: 0;">Documents</h1>

            <h3 class="portal-documents-section-title">IT Guidelines and Policy</h3>
            <div class="row">
                @include('portal.partials.document_card', [
                    'title' => 'IT Guidelines and Policy',
                    'url' => url('/itguidelines'),
                    'type' => 'GUIDE',
                    'category' => 'IT',
                    'icon' => 'fas fa-file-alt',
                ])
            </div>

            @if(count($policiesAllDept) > 0)
                <h3 class="portal-documents-section-title">Operational Policies (All Departments)</h3>
                <div class="row">
                    @foreach($policiesAllDept as $policy)
                        @php
                            $policyUrl = \App\Http\Controllers\PortalController::policyFileUrl($policy);
                            $policyTitle = $policy->subject ?? $policy->description ?? 'Policy';
                            $policyExt = strtoupper(pathinfo((string) $policy->file_attachment, PATHINFO_EXTENSION) ?: '');
                            $policyDate = ! empty($policy->created_at)
                                ? \Carbon\Carbon::parse($policy->created_at)->format('Y-m-d h:i A')
                                : null;
                            $policyDesc = (! empty($policy->description) && ($policy->subject ?? '') !== $policy->description)
                                ? $policy->description
                                : null;
                        @endphp
                        @include('portal.partials.document_card', [
                            'title' => $policyTitle,
                            'url' => $policyUrl,
                            'type' => $policyExt !== '' ? $policyExt : null,
                            'category' => 'Operational Policy',
                            'date' => $policyDate,
                            'description' => $policyDesc,
                        ])
                    @endforeach
                </div>
            @endif

            @foreach($policiesByDept as $row)
                @if(count($row['policies']) > 0)
                    <h3 class="portal-documents-section-title">{{ $row['department'] }}</h3>
                    <div class="row">
                        @foreach($row['policies'] as $policy)
                            @php
                                $policyUrl = \App\Http\Controllers\PortalController::policyFileUrl($policy);
                                $policyTitle = $policy->subject ?? $policy->description ?? 'Policy';
                                $policyExt = strtoupper(pathinfo((string) $policy->file_attachment, PATHINFO_EXTENSION) ?: '');
                                $policyDate = ! empty($policy->created_at)
                                    ? \Carbon\Carbon::parse($policy->created_at)->format('Y-m-d h:i A')
                                    : null;
                            @endphp
                            @include('portal.partials.document_card', [
                                'title' => $policyTitle,
                                'url' => $policyUrl,
                                'type' => $policyExt !== '' ? $policyExt : null,
                                'category' => 'Operational Policy',
                                'date' => $policyDate,
                            ])
                        @endforeach
                    </div>
                @endif
            @endforeach

            @if($documents->total() > 0)
                <h3 class="portal-documents-section-title">Uploaded Documents</h3>
                <div class="row">
                    @foreach($documents as $document)
                        @php
                            $media = $document->getFirstMedia('file');
                            $fileType = $media ? strtoupper($media->extension ?: '') : null;
                            $downloadUrl = $media ? route('portal.documents.download', $document) : null;
                        @endphp
                        @include('portal.partials.document_card', [
                            'title' => $document->title,
                            'url' => $downloadUrl,
                            'type' => $fileType,
                            'category' => 'Company Document',
                            'date' => optional($document->created_at)->format('Y-m-d h:i A'),
                            'description' => $document->description,
                        ])
                    @endforeach
                </div>

                @if($documents->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $documents->links() }}
                    </div>
                @endif
            @endif

            @if(
                count($policiesAllDept) === 0
                && collect($policiesByDept)->sum(fn ($row) => count($row['policies'])) === 0
                && $documents->total() === 0
            )
                <div class="alert alert-info mb-0 mt-3">No documents available yet.</div>
            @endif
        </div>
    </div>
</div>
@endsection
