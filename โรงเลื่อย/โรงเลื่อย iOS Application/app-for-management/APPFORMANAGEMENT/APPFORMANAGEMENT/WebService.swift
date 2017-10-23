//
//  WebService.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/24/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import Alamofire
import Foundation
protocol WebServiceResponseProtocol : class  {
    func loginResponse(response:DataResponse<Any>)
    func forgetPasswordResponse(response:DataResponse<Any>)
    func realtimeTransactionResponse(response:DataResponse<Any>)
    func realtimeTransactionDetailResponse(response:DataResponse<Any>)
    func getRealtimeTransactionResponse(response:DataResponse<Any>)
    func woodpiecesResponse(response:DataResponse<Any>)
    func incomingoutcomingResponse(response:DataResponse<Any>)
    func profitlossDetailResponse(response:DataResponse<Any>)
    func intensivePerformanceResponse(response:DataResponse<Any>)
    func firewoodResponse(response:DataResponse<Any>)
    func weightoutcoming(response:DataResponse<Any>)
    func checkwoodpiecesResponse(response:DataResponse<Any>)
    func checkfirewoodResponse(response:DataResponse<Any>)
    func checkweightoutcomingResponse(response:DataResponse<Any>)
    func checkprofitlossResponse(response:DataResponse<Any>)
}

class WebService: NSObject {
    weak var delegate:WebServiceResponseProtocol?
    private let httpclient = httpClientUtil()
    
    class var shareInstance : WebService{
        struct Static{
            static let instance = WebService()
        }
        return Static.instance
    }
    //MARK: func (method:get,post) 
    /*
     * Key : Int (enum)
     * parameter : [String:String] NSDictionary
     */
    
    func login(parameter:Parameters ,Key:Int ){
        let loginUrlString = String(format: "%@api/user/user-login",SERVER_PATH)
        httpclient.delegate = self;
        httpclient.postDataToWS(requestURL: loginUrlString, parameter: parameter, Key: Key);
    }
    
    func forgetPassword(parameter:Parameters, Key:Int){
        let forgetPasswordUrlString = String(format: "%@api/user/forget-password",SERVER_PATH)
        httpclient.delegate = self;
        httpclient.postDataToWS(requestURL: forgetPasswordUrlString, parameter: parameter, Key: Key)
    }
    /*
     * Realtime Report
     */
    func postRealtimeTransaction(parameter:Parameters ,Key:Int){
        let realtimetransactionUrlString = String(format: "%@api/transaction/realtime-transaction",SERVER_PATH)
        httpclient.delegate = self;
        httpclient.postDataToWS(requestURL: realtimetransactionUrlString, parameter: parameter, Key: Key)
    }
    
    func postRealtimeTransactionDetail(parameter:Parameters,Key:Int){
        let detailUrlString = String(format: "%@api/transaction/transaction-detail",SERVER_PATH)
        httpclient.delegate = self;
        httpclient.postDataToWS(requestURL: detailUrlString, parameter: parameter, Key: Key)
    }
    
    func postRealtimeReport(parameter:Parameters,Key:Int){
        let postRealtimeReport = String(format: "%@api/transaction/realtime-report",SERVER_PATH)
        httpclient.delegate = self;
        httpclient.postDataToWS(requestURL: postRealtimeReport, parameter: parameter, Key: Key)
    }
    /*
     * END
     */
    
    /*
     * Dialy Report
     */
    func postWoodPieces(parameter:Parameters , Key:Int){
        let woodpiecesUrlString = String(format: "%@api/dialyreport/wood-pieces",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: woodpiecesUrlString, parameter: parameter, Key: Key)
    }
    
    func postFireWood(parameter:Parameters , Key:Int){
        let firewoodUrlString = String(format: "%@api/dialyreport/fire-wood",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: firewoodUrlString, parameter: parameter, Key: Key)
    }
    func postWeightOutcoming(parameter:Parameters , Key:Int){
        let weightoutUrlString = String(format: "%@api/dialyreport/weight-outcoming",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: weightoutUrlString, parameter: parameter, Key: Key)
    }
    func postCheckWoodpieces(parameter:Parameters , Key:Int){
        let stringurl = String(format: "%@api/dialyreport/check-woodpieces",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: stringurl, parameter: parameter, Key: Key)
    }
    func postCheckFirewood(parameter:Parameters , Key:Int){
        let stringurl = String(format: "%@api/dialyreport/check-firewood",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: stringurl, parameter: parameter, Key: Key)
    }
    func postCheckWeightoutcoming(parameter:Parameters , Key:Int){
        let stringurl = String(format: "%@api/dialyreport/check-weightoutcoming",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: stringurl, parameter: parameter, Key: Key)
    }
    /*
     * END
     */
    
    /*
     * ProfitandLoss Report
     */
    func postIncomingOutcoming(parameter:Parameters,Key:Int){
        let InOutComingUrlString = String(format: "%@api/profit/incoming-outcoming",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: InOutComingUrlString, parameter: parameter, Key: Key)
    }
    func postProfitLossDetail(parameter:Parameters,Key:Int){
        let ProfitLossUrlString = String(format: "%@api/profit/profit-loss",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: ProfitLossUrlString, parameter: parameter, Key: Key)
    }
    func postCheckProfitloss(parameter:Parameters , Key:Int){
        let urlstring = String(format: "%@api/profit/check-profitloss",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: urlstring, parameter: parameter, Key: Key)
    }
    /*
     * END
     */
    
    /*
     * Performance Report
     */
    func postIntensivePerformance(parameter:Parameters,Key:Int){
        let intensiveUrlString = String(format : "%@api/performance/intensive-performance",SERVER_PATH)
        httpclient.delegate = self
        httpclient.postDataToWS(requestURL: intensiveUrlString, parameter: parameter, Key: Key)
    }
    
    
}
//MARK: httpClientUtil Protocol
extension WebService : httpClientUtilDelegate {
    func onSuccess(response: DataResponse<Any>, Key: Int) {
        switch Key {
            
        case ServiceKey.LOGIN_USER_RESPONSE.rawValue:
            self.delegate?.loginResponse(response: response);
            break
            
        case ServiceKey.FORGET_PASSWORD_RESPONSE.rawValue:
            self.delegate?.forgetPasswordResponse(response: response)
            break
            
        case ServiceKey.SAWMILL_REALTIME_TRANSACTION.rawValue:
            self.delegate?.realtimeTransactionResponse(response: response)
            break
            
        case ServiceKey.SAWMILL_REALTIME_TRANSACTION_DETAIL.rawValue:
            self.delegate?.realtimeTransactionDetailResponse(response: response)
            break
            
        case ServiceKey.SAWMILL_POST_REALTIME_TRANSACTION.rawValue:
            self.delegate?.getRealtimeTransactionResponse(response: response)
            break
            
        case ServiceKey.WOOD_PIECES.rawValue:
            self.delegate?.woodpiecesResponse(response: response)
            break
            
        case ServiceKey.INCOMING_OUTCOMING.rawValue:
            self.delegate?.incomingoutcomingResponse(response: response)
            break
            
        case ServiceKey.PROFIT_LOSS_DETAIL.rawValue:
            self.delegate?.profitlossDetailResponse(response: response)
            break
            
        case ServiceKey.INTENSIVE_PERFORMANCE.rawValue:
            self.delegate?.intensivePerformanceResponse(response: response)
            break
            
        case ServiceKey.FIRE_WOOD.rawValue:
            self.delegate?.firewoodResponse(response: response)
            break
            
        case ServiceKey.WEIGHT_OUT_COMING.rawValue:
            self.delegate?.weightoutcoming(response: response)
            break
            
        case ServiceKey.CHECK_WOOD_PIECES.rawValue:
            self.delegate?.checkwoodpiecesResponse(response: response)
            break
            
        case ServiceKey.CHECK_FIRE_WOOD.rawValue:
            self.delegate?.checkfirewoodResponse(response: response)
            break
            
        case ServiceKey.CHECK_WEIGHT_OUTCOMING.rawValue:
            self.delegate?.checkweightoutcomingResponse(response: response)
            break
            
        case ServiceKey.CHECK_PROFIT_LOSS.rawValue:
            self.delegate?.checkprofitlossResponse(response: response)
            break
        default:
            break
        }
    }
    
    func onFailure(message: String, statuscode: NSInteger, Key: Int) {
        switch Key {
            
        case ServiceKey.LOGIN_USER_RESPONSE.rawValue:
            print("LOGIN_USER_RESPONSE_ERROR_ERROR" , message , "::" , statuscode)
            break
            
        case ServiceKey.FORGET_PASSWORD_RESPONSE.rawValue:
            print("FORGET_PASSWORD_RESPONSE_ERROR" , message , "::" , statuscode)
            break
            
        case ServiceKey.SAWMILL_REALTIME_TRANSACTION.rawValue:
            print("SAWMILL_REALTIME_TRANSACTION_RESPONSE_ERROR" , message , "::" , statuscode)
            break
            
        case ServiceKey.SAWMILL_REALTIME_TRANSACTION_DETAIL.rawValue:
            print("SAWMILL_REALTIME_TRANSACTION_DETAIL_ERROR" , message , "::" , statuscode)
            break
            
        default:
            break
            
        }
    }
}




