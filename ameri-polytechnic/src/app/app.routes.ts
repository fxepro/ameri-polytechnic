
import { Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home';
import { AdmissionsComponent } from './admissions/admissions';
import { ProgramsComponent } from './pages/programs/programs';
import { AboutComponent } from './pages/about/about';
import { ContactComponent } from './pages/contact/contact';
import { ApplyComponent } from './pages/apply/apply';

export const routes: Routes = [
    { path: '', component: HomeComponent },
    { path: 'admissions', component: AdmissionsComponent },
    { path: 'apply', component: ApplyComponent },
    { path: 'programs', component: ProgramsComponent },
    { path: 'about', component: AboutComponent },
    { path: 'contact', component: ContactComponent },
];
