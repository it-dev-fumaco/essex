<ul class="nav nav-tabs" style="text-align: center;">
    <li class="{{ $current_page == '/examPanel' ? 'active' : null }}"><a href="/examPanel">Create Exam</a></li>
    <li class="{{ $current_page == '/tabExams' ? 'active' : null }}"><a href="/tabExams">Exam List</a></li>
    <li class="{{ $current_page == '/tabApplicants' ? 'active' : null }}"><a href="/tabApplicants">Applicant(s)</a></li>
    <li class="{{ $current_page == '/tabExaminees' ? 'active' : null }}"><a href="/tabExaminees">Examinee(s)</a></li>
    <li class="{{ $current_page == '/tabExamReport' ? 'active' : null }}"><a href="/tabExamReport">Examination Report</a></li>
    {{-- <li class="{{ $current_page == '/tabFrequentMistakes' ? 'active' : null }}"><a href="/tabFrequentMistakes">Exam Frequent Mistakes</a></li> --}}
 </ul>