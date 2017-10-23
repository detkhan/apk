//
//  httpClientUtil.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/23/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import Alamofire

protocol httpClientUtilDelegate : class {
    func onSuccess(response:DataResponse<Any> ,Key:Int);
    func onFailure(message:String,statuscode:NSInteger,Key:Int);
}

class httpClientUtil: NSObject {
    weak var delegate:httpClientUtilDelegate?
    
    /*
     post request to server and get response.
     parameter : String requestURL , NSDictionary Json data to send , NSString (HTTP Method = POST) Header [String:String]
     Alamofire.request(url, method: .post, parameters: param, encoding: JSONEncoding.default, headers: [AUTH_TOKEN_KEY : AUTH_TOKEN])
     */
    func postDataToWS(requestURL:String ,parameter:Parameters, Key:Int){
        if(!requestURL.isEmpty){
            Alamofire.request(requestURL,method:.post,parameters:parameter,encoding: JSONEncoding.default)
                .validate(statusCode: 200..<300)
                .validate(contentType: ["application/json"])
                .responseJSON(completionHandler: { (responseData) in
                    switch responseData.result{
                    case .success:
                        self.delegate?.onSuccess(response:responseData, Key:Key);
                    case .failure(let error):
                        self.delegate?.onFailure(message: error.localizedDescription, statuscode: error._code, Key: Key)
                    }
                });
        }
    }
    
    /*
     get request to server and get response.
     parameters : String requestURL, NSString(HTTP method = "GET")
     */
    func getDataFromWS(requestURL:String ,Key:Int){
        if(!requestURL.isEmpty){
            Alamofire.request(requestURL,method:.get,encoding: JSONEncoding.default)
                .validate(statusCode: 200..<300)
                .validate(contentType: ["application/json"])
                .responseJSON(completionHandler: { (responseData) in
                    switch responseData.result{
                    case .success:
                        self.delegate?.onSuccess(response: responseData, Key: Key);
                    case .failure(let error):
                        self.delegate?.onFailure(message: error.localizedDescription, statuscode: error._code, Key: Key)
                    }
                });
        }
    }
    
}
