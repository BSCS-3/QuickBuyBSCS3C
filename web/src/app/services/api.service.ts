import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
    
    //Base URL naten
  private baseUrl = 'http://localhost/QuickBuyBSCS3C/api'; 

  constructor(private http: HttpClient) {}

    // For ALL get methods
  get(endpoint: string) {
    return this.http.get(`${this.baseUrl}/${endpoint}`);
  }

    // For ALL post methods
  post(endpoint: string, data: any) {
    return this.http.post(`${this.baseUrl}/${endpoint}`, data);
  }
}