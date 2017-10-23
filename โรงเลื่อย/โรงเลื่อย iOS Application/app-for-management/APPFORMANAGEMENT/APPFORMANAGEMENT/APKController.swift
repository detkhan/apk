//
//  APKController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 3/23/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import Alamofire
//MARK : self.delegate?.function to other viewcontroller
protocol LoginDelegate : class {
    func loginRes(response: DataResponse<Any>)
}

protocol ForgetpasswordDelegate : class {
    func forgetPasswordRes(response:DataResponse<Any>)
}

protocol RealTimeTransactionDelegate : class {
    //post sawId to get response from service and show in tableview
    func realtimetransactionRes(response:DataResponse<Any>)
    func realtimetransactionDetailRes(response:DataResponse<Any>)
    func getRealtimeTransactionRes(response:DataResponse<Any>)
}

protocol DialyDelegate : class {
    func woodpiecesRes(response:DataResponse<Any>)
    func firewoodRes(response:DataResponse<Any>)
    func weightoutcomingRes(response:DataResponse<Any>)
    func checkwoodpiecesRes(response:DataResponse<Any>)
    func checkfirewoodRes(response:DataResponse<Any>)
    func checkweightoutcoming(response:DataResponse<Any>)
}

protocol ProfitLossDelegate : class {
    func incomingoutcomingRes(response:DataResponse<Any>)
    func profitlossdetailRes(response:DataResponse<Any>)
    func checkprofitlossRes(response:DataResponse<Any>)
}

protocol PerformanceDelegate : class {
    func intensivePerformanceRes(response:DataResponse<Any>)
}

class APKController: NSObject {
    private let webservice = WebService()
    //MARK : name of other delegate
    weak var logindelegate:LoginDelegate?
    weak var forgetpassworddelegate:ForgetpasswordDelegate?
    weak var realtimedelegate:RealTimeTransactionDelegate?
    weak var dialydelegate:DialyDelegate?
    weak var profitlossdelegate:ProfitLossDelegate?
    weak var performancedelegate:PerformanceDelegate?
    class var shareInstance:APKController{
        struct Static{
            static let instance = APKController()
        }
        return Static.instance
    }
    
    func userlogin(parameter:Parameters,Key:Int){
        webservice.delegate  = self;
        webservice.login(parameter: parameter, Key: Key);
    }
    
    func userForgetPassword(parameter:Parameters,Key:Int){
        webservice.delegate = self;
        webservice.forgetPassword(parameter: parameter, Key: Key)
    }
    /*
     * Realtime Report
     */
    func sawmillRealTimeTransaction(parameter:Parameters,Key:Int){
        webservice.delegate = self;
        webservice.postRealtimeTransaction(parameter: parameter, Key: Key)
    }
    
    func sawmillRealTimeTransactionDetail(parameter:Parameters,Key:Int){
        webservice.delegate = self;
        webservice.postRealtimeTransactionDetail(parameter: parameter, Key: Key)
    }
    
    func sawmillPostRealTimeReport(parameter:Parameters,Key:Int){
        webservice.delegate = self;
        webservice.postRealtimeReport(parameter: parameter, Key: Key)
    }
    /*
     * END
     */
    
    /*
     * Dialy Report
     */
    func woodPieces(parameter:Parameters,Key:Int){
        webservice.delegate = self
        webservice.postWoodPieces(parameter: parameter, Key: Key)
    }
    func fireWood(parameter:Parameters, Key:Int){
        webservice.delegate = self
        webservice.postFireWood(parameter: parameter, Key: Key)
    }
    func weightOutcoming(parameter:Parameters , Key:Int){
        webservice.delegate = self
        webservice.postWeightOutcoming(parameter: parameter, Key: Key)
    }
    func checkwoodpieces(parameter:Parameters , Key:Int){
        webservice.delegate = self
        webservice.postCheckWoodpieces(parameter: parameter, Key: Key)
    }
    func checkfirewood(parameter:Parameters , Key:Int){
        webservice.delegate = self
        webservice.postCheckFirewood(parameter: parameter, Key: Key)
    }
    func checkweightoutcoming(parameter:Parameters , Key:Int){
        webservice.delegate = self
        webservice.postCheckWeightoutcoming(parameter: parameter, Key: Key)
    }
    /*
     * END
     */
    
    /*
     * ProfitandLoss Report
     */
    func IncomingOutcoming(parameter:Parameters,Key:Int){
        webservice.delegate = self
        webservice.postIncomingOutcoming(parameter: parameter, Key: Key)
    }
    func ProfitLossDetail(parameter:Parameters,Key:Int){
        webservice.delegate = self
        webservice.postProfitLossDetail(parameter: parameter, Key: Key)
    }
    func checkProfitloss(parameter:Parameters , Key:Int){
        webservice.delegate = self
        webservice.postCheckProfitloss(parameter: parameter, Key: Key)
    }
    /*
     * END
     */
    
    /*
     * Performance Report
     */
    func IntensivePerformance(parameter:Parameters,Key:Int){
        webservice.delegate = self
        webservice.postIntensivePerformance(parameter: parameter, Key: Key)
    }
}

 //MARK: - Response when success to request data from service
extension APKController : WebServiceResponseProtocol{
    func loginResponse(response: DataResponse<Any>) {
        self.logindelegate?.loginRes(response: response);
    }
    func forgetPasswordResponse(response: DataResponse<Any>) {
        self.forgetpassworddelegate?.forgetPasswordRes(response: response)
    }
    func realtimeTransactionResponse(response: DataResponse<Any>) {
        self.realtimedelegate?.realtimetransactionRes(response: response)
    }
    func realtimeTransactionDetailResponse(response: DataResponse<Any>) {
        self.realtimedelegate?.realtimetransactionDetailRes(response: response)
    }
    func getRealtimeTransactionResponse(response: DataResponse<Any>) {
        self.realtimedelegate?.getRealtimeTransactionRes(response: response)
    }
    func woodpiecesResponse(response: DataResponse<Any>) {
        self.dialydelegate?.woodpiecesRes(response: response)
    }
    func incomingoutcomingResponse(response: DataResponse<Any>) {
        self.profitlossdelegate?.incomingoutcomingRes(response: response)
    }
    func profitlossDetailResponse(response: DataResponse<Any>) {
        self.profitlossdelegate?.profitlossdetailRes(response: response)
    }
    func intensivePerformanceResponse(response: DataResponse<Any>) {
        self.performancedelegate?.intensivePerformanceRes(response: response)
    }
    func firewoodResponse(response: DataResponse<Any>) {
        self.dialydelegate?.firewoodRes(response: response)
    }
    func weightoutcoming(response: DataResponse<Any>) {
        self.dialydelegate?.weightoutcomingRes(response: response)
    }
    func checkwoodpiecesResponse(response: DataResponse<Any>) {
        self.dialydelegate?.checkwoodpiecesRes(response: response)
    }
    func checkfirewoodResponse(response: DataResponse<Any>) {
        self.dialydelegate?.checkfirewoodRes(response: response)
    }
    func checkweightoutcomingResponse(response: DataResponse<Any>) {
        self.dialydelegate?.checkweightoutcoming(response: response)
    }
    func checkprofitlossResponse(response: DataResponse<Any>) {
        self.profitlossdelegate?.checkprofitlossRes(response: response)
    }
}

