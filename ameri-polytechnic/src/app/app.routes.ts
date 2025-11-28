
import { Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home';
import { AdmissionsComponent } from './admissions/admissions';
import { ProgramsComponent } from './pages/programs/programs';
import { ProgramDetailComponent } from './pages/program-detail/program-detail';
import { AboutComponent } from './pages/about/about';
import { ContactComponent } from './pages/contact/contact';
import { ApplyComponent } from './pages/apply/apply';
import { LoginComponent } from './pages/login/login';
import { RegisterComponent } from './pages/register/register';
import { VerifyEmailComponent } from './pages/verify-email/verify-email';
import { authGuard } from './guards/auth.guard';

export const routes: Routes = [
    { path: '', component: HomeComponent },
    { path: 'admissions', component: AdmissionsComponent },
    { path: 'apply', component: ApplyComponent, canActivate: [authGuard] },
    { path: 'programs', component: ProgramsComponent },
    { path: 'programs/:id', component: ProgramDetailComponent },
    { path: 'about', component: AboutComponent },
    { path: 'contact', component: ContactComponent },
    { path: 'login', component: LoginComponent },
    { path: 'register', component: RegisterComponent },
    { path: 'verify-email', component: VerifyEmailComponent },
];
