import { ChangeDetectionStrategy, Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-apply',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './apply.html',
  styleUrls: ['./apply.css'],
  changeDetection: ChangeDetectionStrategy.OnPush,
})
export class ApplyComponent {
  applyForm: FormGroup;
  submitted = false;
  isSubmitting = false;

  programs = [
    'Cybersecurity & Digital Defense',
    'Robotics & Automation Technology',
    'Electrical Technology',
    'HVAC & Refrigeration Technology',
    'Construction Technology',
    'Web & Application Development',
    'Unmanned Aerial Systems (UAS) – Drone Operations',
    'Information Technology Support & Systems Administration',
    'Plumbing Technology',
    'Welding & Metal Fabrication',
    'Medical Assisting Technology',
    'Emergency Medical Technician (EMT)',
  ];

  startDates = [
    'Spring 2025',
    'Summer 2025',
    'Fall 2025',
    'Spring 2026',
  ];

  constructor(private fb: FormBuilder) {
    this.applyForm = this.fb.group({
      // Personal Information
      firstName: ['', [Validators.required]],
      lastName: ['', [Validators.required]],
      email: ['', [Validators.required, Validators.email]],
      phone: ['', [Validators.required]],
      dateOfBirth: ['', [Validators.required]],
      
      // Address
      address: ['', [Validators.required]],
      city: ['', [Validators.required]],
      state: ['', [Validators.required]],
      zipCode: ['', [Validators.required]],
      
      // Education
      educationLevel: ['', [Validators.required]],
      graduationYear: [''],
      
      // Program Selection
      programOfInterest: ['', [Validators.required]],
      preferredStartDate: ['', [Validators.required]],
      
      // Additional Information
      howDidYouHear: [''],
      previousEducation: [''],
      workExperience: [''],
      questions: [''],
      
      // Agreement
      agreeToTerms: [false, [Validators.requiredTrue]],
    });
  }

  onSubmit() {
    this.submitted = true;
    
    if (this.applyForm.valid) {
      this.isSubmitting = true;
      
      // Simulate form submission
      setTimeout(() => {
        console.log('Form submitted:', this.applyForm.value);
        this.isSubmitting = false;
        alert('Thank you! Your application has been submitted. Our admissions team will review it and contact you within 48 hours.');
        this.applyForm.reset();
        this.submitted = false;
      }, 1500);
    }
  }

  get f() {
    return this.applyForm.controls;
  }
}

